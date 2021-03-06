<?php
/**
 * @package   jInbound
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2013-2015 Anything-Digital.com
 * @copyright 2016-2021 Joomlashack.com. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 * This file is part of jInbound.
 *
 * jInbound is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * jInbound is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with jInbound.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('_JEXEC') or die;

function JinboundBuildRoute(&$query)
{
    $segments = array();

    $app  = JFactory::getApplication();
    $menu = $app->getMenu();

    if (empty($query['Itemid'])) {
        $menuItem = $menu->getActive();
    } else {
        $menuItem = $menu->getItem($query['Itemid']);
    }

    $view = null;
    if (!empty($query['view'])) {
        $view = $query['view'];
        unset($query['view']);
    } elseif ($menuItem && !empty($menuItem->query['view'])) {
        $view = $menuItem->query['view'];
    }

    $id = null;
    if (!empty($query['id'])) {
        $id = $query['id'];
        unset($query['id']);
    } elseif ($menuItem && !empty($menuItem->query['id'])) {
        $id = $menuItem->query['id'];
    }

    // check page view
    if ($view == 'page' && $id) {
        $pid = $id;
        if (strpos($id, ':') !== false) {
            list ($pid, $tmp) = explode(':', $id, 2);

        } else {
            $tmp = '';
        }
        if ($pid = (int)$pid) {
            $db = JFactory::getDbo();
            $db->setQuery(
                $db->getQuery(true)
                    ->select(
                        array(
                            $db->quoteName('category'),
                            $db->quoteName('name'),
                            $db->quoteName('alias')
                        )
                    )
                    ->from('#__jinbound_pages')
                    ->where($db->quoteName('id') . ' = ' . $pid)
            );

            if ($record = $db->loadObject()) {
                if (empty($tmp)) {
                    $id .= ':'
                        . (empty($record->alias)
                            ? JApplicationHelper::stringURLSafe($record->name)
                            : $record->alias);
                }
                $categories = JCategories::getInstance('Jinbound');
                $category   = $categories->get($record->category);
                if ($category) {
                    $path = $category->getPath();
                    $path = array_reverse($path);

                    $array = array();
                    foreach ($path as $cid) {
                        $array[] = $cid;
                    }
                    $segments = array_merge($segments, array_reverse($array));
                }

            } else {
                $id = null;
            }
        }

        if ($id) {
            $segments[] = $id;
        }
    }

    if (isset($query['layout'])) {
        if ($query['layout'] == 'default') {
            unset($query['layout']);
        }
    }

    return $segments;
}

function JinboundParseRoute($segments)
{
    $vars = array();

    /** @var JApplicationSite $app */
    $app  = JFactory::getApplication();
    $menu = $app->getMenu();
    $item = $menu->getActive();

    if (is_object($item)) {
        $templateStyle = $item->template_style_id;
        if ($templateStyle) {
            JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_templates/tables');

            /** @var TemplatesTableStyle $template */
            $template = JTable::getInstance('Style', 'TemplatesTable');
            $template->load($templateStyle);
            if ($template->id) {
                $app->setTemplate($template->template, $template->params);
            }
        }
    }

    $count = count($segments);

    if (!isset($item)) {
        $vars['view'] = $segments[0];
        $vars['id']   = $segments[$count - 1];

        return $vars;

    } else {
        if ('page' == $item->query['view']) {
            $vars['view'] = 'page';
            $vars['id']   = $segments[$count - 1];
        }
    }

    return $vars;
}
