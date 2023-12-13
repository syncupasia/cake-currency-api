<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Currency Entity
 *
 * @property int $id
 * @property string $iso_code
 * @property string $name
 * @property string $current_rate
 * @property string $previous_rate
 * @property string $base_currency
 * @property \Cake\I18n\FrozenTime|null $created_at
 * @property \Cake\I18n\FrozenTime|null $updated_at
 */
class Currency extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'iso_code' => true,
        'name' => true,
        'current_rate' => true,
        'previous_rate' => true,
        'base_currency' => true,
        'created_at' => true,
        'updated_at' => true,
    ];
}
