<?php
/**
 * FormIt
 *
 * Copyright 2009 by Shaun McCormick <shaun@collabpad.com>
 *
 * FormIt is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * FormIt is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * FormIt; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package formit
 */
/**
 * FormIt
 *
 * A dynamic form processing Snippet for MODx Revolution 2.0.
 *
 * @version 1.0
 * @author Shaun McCormick <shaun@collabpad.com>
 * @copyright Copyright &copy; 2009
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License
 * version 2 or (at your option) any later version.
 * @package formit
 */
/* make sure appropriate POST occurred */
$submitVar = $modx->getOption('submitVar',$scriptProperties,false);
if (empty($_POST)) return '';
if (!empty($submitVar) && empty($_POST[$submitVar])) return '';

/* load FormIt classes */
require_once $modx->getOption('formit.core_path',null,$modx->getOption('core_path').'components/formit/').'model/formit/formit.class.php';
$fi = new FormIt($modx,$scriptProperties);
$fi->initialize($modx->context->get('key'));

/* get default properties */
$hooks = $modx->getOption('hooks',$scriptProperties,'');

/* validate fields */
$fi->loadValidator();
$fields = $fi->validator->validateFields($_POST);

if (empty($fi->validator->errors)) {
    $fi->loadHooks();

    $fi->hooks->loadMultiple($hooks,$fields);

    /* process form */
    if (!empty($fi->hooks->errors)) {
        $errorMsg = $fi->hooks->getErrorMessage();

        $modx->toPlaceholder('error_message',$errorMsg,'fi.error');
    }
}
$modx->toPlaceholders($fi->validator->errors,'fi.error');
$modx->toPlaceholders($fields,'fi');

return '';