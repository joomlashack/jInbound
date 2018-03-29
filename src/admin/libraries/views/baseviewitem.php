<?php
/**
 * @package             jInbound
 * @subpackage          com_jinbound
 **********************************************
 * jInbound
 * Copyright (c) 2013 Anything-Digital.com
 * Copyright (c) 2018 Open Source Training, LLC
 **********************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.n *
 * This header must not be removed. Additional contributions/changes
 * may be added to this header as long as no information is deleted.
 */

use Joomla\Registry\Registry;

defined('JPATH_PLATFORM') or die;

class JInboundItemView extends JInboundView
{
    /**
     * @var object
     */
    protected $item = null;

    /**
     * @var JForm
     */
    protected $form = null;

    /**
     * @param string $tpl
     * @param bool   $safeparams
     *
     * @return bool|void
     * @throws Exception
     */
    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode('<br />', $errors), 500);
        }

        if ($this->item && !property_exists($this->item, 'id')) {
            $this->item->id = 0;
        }

        $this->canDo = JInbound::getActions();

        $this->prepareItem();

        parent::display($tpl);

        $this->setDocument();
    }

    public function prepareItem()
    {
        // stub
    }

    /**
     * @return void
     * @throws Exception
     */
    public function setDocument()
    {
        $app = JFactory::getApplication();

        if ($app->isClient('site')) {
            $menus = $app->getMenu();
            if ($menu = $menus->getActive()) {
                $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
            }


            if ($this->params->get('menu-meta_description')) {
                $this->document->setDescription($this->params->get('menu-meta_description'));
            }

            if ($this->params->get('menu-meta_keywords')) {
                $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
            }

            if ($this->params->get('robots')) {
                $this->document->setMetadata('robots', $this->params->get('robots'));
            }

            $this->setTitle();
        }
    }

    /**
     * @param string $title
     *
     * @throws Exception
     */
    protected function setTitle($title = null)
    {
        $app = JFactory::getApplication();

        if ($app->isClient('site')) {
            /** @var Registry $params */
            $params = $app->getParams();

            $title = $title ?: $params->get('page_title', '');

            if (empty($title)) {
                $title = $app->get('sitename');

            } elseif ($app->get('sitename_pagetitles', 0) == 1) {
                $title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);

            } elseif ($app->get('sitename_pagetitles', 0) == 2) {
                $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
            }

            $this->document->setTitle($title);
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function addToolBar()
    {
        // only fire in administrator
        $app = JFactory::getApplication();
        if (!$app->isAdmin()) {
            return;
        }
        $app->input->set('hidemainmenu', true);
        $user       = JFactory::getUser();
        $userId     = $user->id;
        $isNew      = (@$this->item->id == 0);
        $checkedOut = false;
        $name       = strtolower($this->_name);
        if ($this->item && property_exists($this->item, 'checked_out')) {
            $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
        }
        $canCreate  = $user->authorise('core.create', JInbound::COM . ".$name");
        $canEdit    = $user->authorise('core.edit', JInbound::COM . ".$name");
        $canEditOwn = $user->authorise('core.edit.own', JInbound::COM . ".$name");

        // set the toolbar title
        $title = strtoupper(JInbound::COM . '_' . $this->_name . '_MANAGER');
        $class = 'jinbound-' . strtolower($this->_name);
        if ('contact' === $this->_name) {
            $title = strtoupper(JInbound::COM . '_LEAD_MANAGER');
            $class = 'jinbound-contact';
        }
        $title .= '_' . ($checkedOut ? 'VIEW' : ($isNew ? 'ADD' : 'EDIT'));
        JToolBarHelper::title(JText::_($title), $class);

        if ($isNew) {
            if ($canCreate) {
                JToolBarHelper::apply($name . '.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save($name . '.save', 'JTOOLBAR_SAVE');
                JToolBarHelper::custom($name . '.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW',
                    false);
            }
            JToolBarHelper::cancel($name . '.cancel', 'JTOOLBAR_CANCEL');
        } else {
            // Can't save the record if it's checked out.
            if (!$checkedOut) {
                // Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
                if ($canEdit || ($canEditOwn && $this->item->created_by == $userId)) {
                    JToolBarHelper::apply($name . '.apply', 'JTOOLBAR_APPLY');
                    JToolBarHelper::save($name . '.save', 'JTOOLBAR_SAVE');

                    // We can save this record, but check the create permission to see if we can return to make a new one.
                    if ($canCreate) {
                        JToolBarHelper::custom($name . '.save2new', 'save-new.png', 'save-new_f2.png',
                            'JTOOLBAR_SAVE_AND_NEW', false);
                    }
                }
            }

            // If checked out, we can still save
            if ($canCreate) {
                JToolBarHelper::custom($name . '.save2copy', 'save-copy.png', 'save-copy_f2.png',
                    'JTOOLBAR_SAVE_AS_COPY', false);
            }
            JToolBarHelper::cancel($name . '.cancel', 'JTOOLBAR_CLOSE');
        }
    }

    public function addMenuBar()
    {
        if ('edit' == JFactory::getApplication()->input->get('layout')) {
            return;
        }
        parent::addMenuBar();
    }
}
