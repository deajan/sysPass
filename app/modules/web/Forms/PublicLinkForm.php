<?php
/**
 * sysPass
 *
 * @author nuxsmin
 * @link https://syspass.org
 * @copyright 2012-2018, Rubén Domínguez nuxsmin@$syspass.org
 *
 * This file is part of sysPass.
 *
 * sysPass is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * sysPass is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 *  along with sysPass.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace SP\Modules\Web\Forms;

use SP\Core\Acl\ActionsInterface;
use SP\Core\Exceptions\ValidationException;
use SP\DataModel\PublicLinkData;
use SP\Http\Request;
use SP\Mgmt\PublicLinks\PublicLink;
use SP\Util\Util;

/**
 * Class PublicLinkForm
 *
 * @package SP\Modules\Web\Forms
 */
class PublicLinkForm extends FormBase implements FormInterface
{
    /**
     * @var PublicLinkData
     */
    protected $publicLinkData;

    /**
     * Validar el formulario
     *
     * @param $action
     * @return bool
     * @throws ValidationException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function validate($action)
    {
        switch ($action) {
            case ActionsInterface::PUBLICLINK_CREATE:
            case ActionsInterface::PUBLICLINK_EDIT:
                $this->analyzeRequestData();
                $this->checkCommon();
                break;
        }

        return true;
    }

    /**
     * Analizar los datos de la petición HTTP
     *
     * @return void
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    protected function analyzeRequestData()
    {
        $this->publicLinkData = new PublicLinkData();
        $this->publicLinkData->setId($this->itemId);
        $this->publicLinkData->setTypeId(PublicLink::TYPE_ACCOUNT);
        $this->publicLinkData->setItemId(Request::analyzeInt('accountId'));
        $this->publicLinkData->setNotify(Request::analyzeBool('notify', false));
        $this->publicLinkData->setHash(Util::generateRandomBytes());
    }

    /**
     * @throws ValidationException
     */
    protected function checkCommon()
    {
        if (!$this->publicLinkData->getItemId()) {
            throw new ValidationException(__u('Es necesario una cuenta'));
        }
    }

    /**
     * @return mixed
     */
    public function getItemData()
    {
        return $this->publicLinkData;
    }
}