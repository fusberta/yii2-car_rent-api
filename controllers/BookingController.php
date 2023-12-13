<?php
namespace app\controllers;

use app\models\Booking;
use app\models\Cars;
use app\models\Users;
use Yii;
use yii\rest\Controller;

class BookingController extends Controller
{
    public $modelClass = 'app\models\Booking';

    private function findUserByToken($token)
    {
        return Users::findOne(['token' => $token]);
    }

    public function actionUserBookings()
    {
        $user = $this->findUserByToken(str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization')));

        if (!$user) {
            $response = $this->response;
            $response->statusCode = 401;
            $response->data = [
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized',
                ],
            ];
            return $response;
        }

        $booking = Booking::findOne(['user_id' => $user->user_id]);

        if (!$booking) {
            $response = $this->response;
            $response->statusCode = 200;
            $response->data = [ "message" => "Вы еще не бронировали ни одного автомобиля"];
            return $response;
        } else {
            $car = Cars::findOne(['car_id' => $booking->car_id]);

            $response = $this->response;
            $response->statusCode = 200;
            $response->data = [
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'car_brand' => $car->brand,
                'car_model' => $car->model,
                'daily_price' => $car->daily_price,
                'start_date' => $booking->start_date,
                'end_date' => $booking->end_date,
                'status' => $booking->status,
            ];
            return $response;
        }
    }
}