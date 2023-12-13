<?php
namespace app\controllers;

use app\models\Booking;
use app\models\Reviews;
use app\models\Users;
use Yii;
use yii\rest\Controller;

class ReviewsController extends Controller
{
    public $modelClass = 'app\models\Reviews';

    public function actionIndex()
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
            $response->statusCode = 401;
            return $response;
        }

        $reviews = Reviews::find()->all();

        if ($reviews) {
            $response = $this->response;
            $response->statusCode = 201;
            $response->data = $reviews;
            return $response;
        } else {
            $response = $this->response;
            $response->statusCode = 204;
            return $response;
        }
    }

    public function actionCreate($id)
    {
        // Проверка, авторизован ли пользователь
        $user = $this->findUserByToken(str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization')));
        $data = Yii::$app->request->getBodyParams();

        if (!$user) {
            $response = $this->response;
            $response->data = [
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized',
                ],
            ];
            $response->statusCode = 401;
            return $response;
        }

        // Получение текущего пользователя и проверка аренды автомобиля за последний месяц
        $lastMonth = new \DateTime('-1 month');
        $recentBooking = Booking::find()
            ->where(['user_id' => $user->user_id, 'car_id' => $id])
            ->andWhere(['>', 'end_date', $lastMonth->format('Y-m-d H:i:s')])
            ->one();

        if (!$recentBooking) {
            $response = $this->response;
            $response->data = [
                'error' => [
                    'code' => 400,
                    'message' => 'Вы не бронировали автомобиль в этом месяце',
                ],
            ];
            $response->statusCode = 400;
            return $response;
        }

        // Создание отзыва
        $model = new Reviews();
        $model->user_id = $user->user_id;
        $model->car_id = $id;
        $model->rating = $data['rating'];
        $model->review_text = $data['review_text'];

        $model->load(Yii::$app->request->post());

        if ($model->save()) {
            $response = $this->response;
            $response->statusCode = 204;
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

    private function findUserByToken($token)
    {
        return Users::findOne(['token' => $token]);
    }
}