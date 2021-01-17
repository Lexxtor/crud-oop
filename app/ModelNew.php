<?php


/**
 * @property int id
 * @property string title
 * @property string text
 */
class ModelNew extends Model
{
    public static $tableName = 'news';

    /**
     * @var array
     */
    protected $attributes = [
        'id' => null,
        'title' => null,
        'text' => null
    ];

    public function validate()
    {
        if (!$this->title) {
            $this->errors[] = 'Заголовок должен быть заполнен.';
        }
        if (mb_strlen($this->title) >= 100) {
            $this->errors[] = 'Заголовок должен быть короче 100 символов.';
        }
        if (!$this->text) {
            $this->errors[] = 'Текст должен быть заполнен.';
        }

        return $this->errors;
    }
}