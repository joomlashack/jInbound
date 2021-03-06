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

defined('JPATH_PLATFORM') or die();

class JInboundModelStatuses extends JInboundListModel
{
    protected $context = 'com_jinbound.statuses';

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'Status.name',
                'Status.published',
                'Status.default',
                'Status.active',
                'Status.final',
                'Status.ordering',
                'Status.description'
            );
        }

        parent::__construct($config);
    }

    /**
     * @return JDatabaseQuery
     */
    protected function getListQuery()
    {
        $db = $this->getDbo();

        $query = $db->getQuery(true)
            ->select('Status.*')
            ->from('#__jinbound_lead_statuses AS Status');

        $this->appendAuthorToQuery($query, 'Status');

        $this->filterSearchQuery(
            $query,
            $this->getState('filter.search'),
            'Status',
            'id',
            array('name', 'description')
        );
        $this->filterPublished($query, $this->getState('filter.published'), 'Status');

        $listOrdering = $this->getState('list.ordering', 'Status.name');
        $listDirn     = $db->escape($this->getState('list.direction', 'ASC'));
        $query->order($db->escape($listOrdering) . ' ' . $listDirn);

        return $query;
    }
}
