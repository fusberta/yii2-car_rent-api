<?php
namespace app\controllers;

use app\models\Users;
use Yii;
use yii\rest\Controller;
use yii\validators\Validator;

class UsersController extends Controller
{
    public $modelClass = 'app\models\Users';

    public function actionCreate()
    {
        $model = new Users();
        $model->load(Yii::$app->request->post(), '');

        $token = $model->register();

        $response = $this->response;

        if ($token !== null) {
            $response->statusCode = 201;
            $response->data = ['data' => ['token' => $token]];
        } else {
            $response->statusCode = 422;
            $response->data = [
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error'
                ],
                'error_description' => $model->errors
            ];
        }

        return $response;
    }

    public function actionLogin()
    {
        $model = new Users();
        $model->load(Yii::$app->request->post(), '');

        $token = $model->login();

        if ($token !== null) {
            $response = $this->response;
            $response->setStatusCode(200);
            $response->data = ['data' => ['token' => $token]];
            return $response;
        } else {
            $this->responseError(401, 'Invalid email or password');
        }

    }

    public function actionUserData()
    {
        $user = $this->findUserByToken(str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization')));

        if (!$user) {
            $this->responseError(401, 'Unauthorized');
        }

        $response = $this->response;
        $response->statusCode = 200;
        $response->data = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
        ];
        return $response;
    }

    public function actionUpdateUserData()
    {
        $user = new Users;
        $user = $this->findUserByToken(str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization')));

        if (!$user) {
            return $this->responseError(401, 'Unauthorized');
        }

        $data = Yii::$app->request->getBodyParams();

        $response = $this->response;

        $this->updateUserAttributes($user, $data);

        if ($user->validate()) {
            $user->save();
            $response->statusCode = 200;
            $response->data = [
                'message' => 'User data updated successfully ',
            ];
            return $response;
        } else {
            $response->statusCode = 422;
            $response->data = [
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error'
                ],
                'error_description' => $user->errors
            ];
        }
    }

    /********************************************************************/

    protected function findUserByToken($token)
    {
        return Users::findOne(['token' => $token]);
    }

    protected function updateUserAttributes($user, $data)
    {
        $attributes = ['first_name', 'last_name', 'email', 'phone', 'address'];

        foreach ($attributes as $attribute) {
            if (isset($data[$attribute])) {
                $user->$attribute = $data[$attribute];
            }
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

}

