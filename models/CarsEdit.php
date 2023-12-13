<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "cars".
 *
 * @property int $car_id
 * @property string|null $brand
 * @property string|null $model
 * @property int|null $year
 * @property string|null $reg_number
 * @property int|null $category_id
 * @property int $daily_price
 * @property string|null $status
 * @property string|null $image
 *
 * @property Booking[] $booking
 * @property Categories $category
 * @property Reviews[] $reviews
 */
class CarsEdit extends Cars
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cars';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    { /* Напишите здесь правила валидации
            в руководстве Yii2
        https://moodle.pkgh.ru/mod/resource/view.php?id=60175
        стр. 509-524
        */

        return [
            [['model', 'brand', 'daily_price', 'reg_number'], 'required'],
            [['daily_price'], 'number', 'min' => 0],
            [['brand', 'model'], 'string', 'max' => 100],
            [['reg_number'], 'string', 'max' => 25],
            [['status'], 'string', 'max' => 20],
            [['image'], 'string', 'max' => 250],
            [['year'], 'number', 'max' => 4],
        ];
    }
}