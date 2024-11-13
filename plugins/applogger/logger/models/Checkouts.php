<?php namespace AppLogger\Logger\Models;

use Model;

/**
 * Checkouts Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Checkouts extends Model // REVIEW - Taký detail - Meno modelu by malo byť vždy v singulári, čiže Checkout
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'applogger_logger_checkouts';

    /**
     * @var array rules for validation
     */
    public $rules = [];
}
