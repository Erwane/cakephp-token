<?php
declare(strict_types=1);

/**
 * CakePHP Token
 * Copyright (c) Erwane BRETON
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Erwane BRETON
 * @see         https://github.com/Erwane/cakephp-token
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Token\Model\Entity;

use Cake\ORM\Entity;

/**
 * Class Token
 *
 * @property string id
 * @property array content
 * @property \Cake\I18n\FrozenTime expire
 * @property \Cake\I18n\FrozenTime created
 */
class Token extends Entity
{
}
