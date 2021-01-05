<?php
declare(strict_types=1);

namespace Token\Model\Entity;

use Cake\ORM\Entity;

/**
 * Class Token
 *
 * @package Token\Model\Entity
 * @property string id
 * @property array content
 * @property \Cake\I18n\FrozenTime expire
 * @property \Cake\I18n\FrozenTime created
 */
class Token extends Entity
{
}
