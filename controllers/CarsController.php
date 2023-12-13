<?php
namespace app\controllers;

use app\models\Booking;
use app\models\Cars;
use app\models\CarsEdit;
use app\models\EditCars;
use app\models\Users;
use Yii;
use yii\rest\Controller;
use yii\web\UploadedFile;

class CarsController extends FunctionController
{
    public $modelClass = 'app\models\Cars';

    public function actionIndex()
    {
        $cars = Cars::find()->all();

        if ($cars) {
            $response = $this->response;
            $response->statusCode = 201;
            $response->data = $cars;
            return $response;
        } else {
            $response = $this->response;
            $response->statusCode = 204;
            return $response;
        }
    }

    public function actionGetOneCar($id)
    {
        $car = Cars::findOne($id);

        if ($car !== null) {
            $response = $this->response;
            $response->data = $car;
            return $response;
        } else {
            $response = $this->response;
            $response->data = [
                'error' => [
                    'code' => 404,
                    'message' => 'Car not found',
                ],
            ];
            $response->statusCode = 404;
            return $response;
        }
    }

    public function actionBooking($id)
    {
        $user = $this->findUserByToken(str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization')));

        if (!$user) {
            $response = $this->response;
            $response->data = [
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized',
                ],
            ];
            ;
            $response->statusCode = 401;
            return $response;
        }

        $car = Cars::findOne($id);

        if (isset(Yii::$app->request->bodyParams['start_date'])) {
            $start_date = Yii::$app->request->bodyParams['start_date'];
        } else {
            $start_date = null;
        }
        $end_date = Yii::$app->request->bodyParams['end_date'];

        if ($car !== null && $car->status !== 'booked') {
            // Начало транзакции, чтобы обеспечить атомарность операций
            $transaction = Yii::$app->db->beginTransaction();

            try {
                // Обновление статуса в таблице cars
                $car->status = 'booked';
                $car->save();

                // Создание записи в таблице Booking
                $booking = new Booking();
                $booking->car_id = $car->car_id;
                $booking->user_id = $user->user_id;
                if ($start_date !== null) {
                    $booking->start_date = $start_date;
                }
                $booking->end_date = $end_date;
                $booking->save();

                // Подтверждение транзакции
                $transaction->commit();

                $response = $this->response;
                $response->data = [
                    'error' => [
                        'code' => 200,
                        'message' => 'Car booked succesfully',
                    ],
                ];
                return $response;
            } catch (\Exception $err) {
                // Откат транзакции в случае ошибки
                $transaction->rollBack();

                Yii::error('Error booking car: ' . $err->getMessage(), 'app\controllers\CarsController');

                $response = $this->response;
                $response->data = ['error' => 'Error booking car: ' . $err->getMessage()];
                $response->statusCode = 500;
                return $response;
            }
        } else if ($car == null) {
            $response = $this->response;
            $response->data = [
                'error' => [
                    'code' => 404,
                    'message' => 'Car not found',
                ],
            ];
            $response->statusCode = 404;
            return $response;
        } else {
            $response = $this->response;
            $response->data = [
                'error' => [
                    'code' => 409,
                    'message' => 'Car is already booked',
                ],
            ];
            $response->statusCode = 409;
            return $response;
        }
    }

    public function actionCreate()
    {
        $user = $this->findUserByToken(str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization')));

        if ($user->role !== 'admin') {
            $response = $this->response;
            $response->data = [
                'error' => [
                    'code' => 403,
                    'message' => "You're not admin",
                ],
            ];
            $response->statusCode = 403;
            return $response;
        }

        $model = new Cars();
        $model->load(Yii::$app->request->post(), '');

        $newCar = $model->addCar();

        if ($newCar) {
            $response = $this->response;
            $response->statusCode = 201;
            $response->data = $newCar;
            return $response;
        } else {
            $response = $this->response;
            $response->statusCode = 422;
            $response->data = [
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                ],
            ];
            return $response;
        }
    }

    public function actionDelete($id)
    {
        $user = $this->findUserByToken(str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization')));

        if ($user->role !== 'admin') {
            $response = $this->response;
            $response->data = [
                'error' => [
                    'code' => 403,
                    'message' => "You're not admin",
                ],
            ];
            $response->statusCode = 403;
            return $response;
        }

        $car = Cars::findOne($id);

        if ($car) {
            $car->delete();
            $response = $this->response;
            $response->statusCode = 204;
            return $response;
        } else {
            $response = $this->response;
            $response->statusCode = 404;
            $response->data = [
                'error' => [
                    'code' => 404,
                    'message' => 'Car not found',
                ],
            ];
            return $response;
        }
    }

    public function actionUpdate($id)
    {
        $user = $this->findUserByToken(str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization')));

        if ($user->role !== 'admin') {
            $this->responseError(403, "You're not admin");
        }

        $car = Cars::findOne($id);
        $carEdit = new CarsEdit();

        $carEdit->load(Yii::$app->getRequest()->getBodyParams(), '');

        if (!$car)
            return $this->responseError(404, 'Car not found');

        $data = Yii::$app->request->getBodyParams();

        $this->updateCarAttributes($car, $data);

        $response = $this->response;

        if ($carEdit->validate()) {
            $car->save();

            $response->statusCode = 200;
            $response->data = [
                'message' => 'Car updated successfully ',
            ];
            return $response;
        } else {
            $this->responseError(422, $carEdit->errors);
        }

    }

    protected function updateCarAttributes($car, $data)
    {
        $attributes = ['brand', 'model', 'year', 'reg_number', 'daily_price', 'status'];

        foreach ($attributes as $attribute) {
            if (isset($data[$attribute])) {
                $car->$attribute = $data[$attribute];
            }
        }
    }

    public function actionUpdateImage($id)
    {
        $user = $this->findUserByToken(str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization')));

        if ($user->role !== 'admin') {
            $this->responseError(403, "You're not admin");
        }

        $car = Cars::findOne($id);

        if (!$car)
            return $this->responseError(404, 'Car not found');

        $car->load(Yii::$app->getRequest()->getBodyParams(), '');

        if (UploadedFile::getInstanceByName('image')) {
            $this->updateCarImage($car);
        }
    }

    protected function responseError($statusCode, $message)
    {
        $response = $this->response;
        $response->setStatusCode($statusCode);
        $response->data = [
            'error' => [
                'code' => $statusCode,
                'message' => $message,
            ],
        ];
        return $response;
    }

    protected function findUserByToken($token)
    {
        return Users::findOne(['token' => $token]);
    }

    private function updateCarImage($car)
    {
        $url = Yii::$app->basePath . $car->image;
        @unlink($url);

        $car->image = UploadedFile::getInstanceByName('image');
        $image_name = '/web/product_photo/image_product_' . Yii::$app->getSecurity()->generateRandomString(40) . '.' . $car->image->extension;
        $car->image = Yii::$app->basePath . $image_name;
        $car->image = $image_name;

        if ($car->save(false)) {
            $response = $this->response;
            $response->data = [
                'error' => [
                    'code' => 200,
                    'message' => 'Car image updated succesfully',
                ],
            ];
            return $response;
        } else {
            return $this->responseError(422, 'Validation error');
        }
    }

}

