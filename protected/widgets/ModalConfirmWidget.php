<?php

/**
 * HumHub
 * Copyright © 2014 The HumHub Project
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 */

/**
 * ModalConfirmWidget shows a confirm modal before calling an action
 *
 * After successful confirmation this widget returns the response of the called action.
 * So be ensure to write an workflow for that inside your controller action. (for example: close modal, reload page etc.)
 *
 * @package humhub.widgets
 * @since 0.5
 * @author Andreas Strobel
 */
class ModalConfirmWidget extends HWidget {

    /**
     * @var String Message to show
     */
    public $uniqueID;

    /**
     * @var String Message to show
     */
    public $message;

    /**
     * @var String button name for confirming
     */
    public $buttonTrue = "Ok";

    /**
     * @var String button name for canceling
     */
    public $buttonFalse = "Cancel";

    /**
     * @var String classes for the displaying link
     */
    public $class;

    /**
     * @var String content for the displaying link
     */
    public $linkContent;

    /**
     * @var String original path to view
     */
    public $linkHref;

    /**
     * @var String Tooltip text
     */
    public $linkTooltipText;

    /**
     * Displays / Run the Widgets
     */
    public function run() {

        $this->render('modalConfirm', array(
            'uniqueID' => $this->uniqueID,
            'message' => $this->message,
            'buttonTrue' => $this->buttonTrue,
            'buttonFalse' => $this->buttonFalse,
            'class' => $this->class,
            'linkContent' => $this->linkContent,
            'linkHref' => $this->linkHref,
            'linkTooltipText' => $this->linkTooltipText
        ));
    }

}

?>