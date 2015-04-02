<?php

namespace Calendar\View\Helper\Cell\Render;

use Square\Entity\Square;
use Zend\View\Helper\AbstractHelper;

class OccupiedForVisitors extends AbstractHelper
{

    public function __invoke(array $reservations, array $cellLinkParams, Square $square)
    {
        $view = $this->getView();

        $reservationsCount = count($reservations);

        if ($reservationsCount > 1) {
            return $view->calendarCellLink('Occupied', $view->url('square', [], $cellLinkParams), 'cc-single');
        } else {
            $reservation = current($reservations);
            $booking = $reservation->needExtra('booking');

            if ($square->getMeta('public_names', 'false') == 'true') {
                $cellLabel = $booking->needExtra('user')->need('alias');
            } else {
                $cellLabel = null;
            }

            $cellGroup = ' cc-group-' . $booking->need('bid');

            switch ($booking->need('status')) {
                case 'single':
                    if (! $cellLabel) {
                        $cellLabel = 'Occupied';
                    }

                    return $view->calendarCellLink($cellLabel, $view->url('square', [], $cellLinkParams), 'cc-single' . $cellGroup);
                case 'subscription':
                    if (! $cellLabel) {
                        $cellLabel = 'Subscription';
                    }

                    return $view->calendarCellLink($cellLabel, $view->url('square', [], $cellLinkParams), 'cc-multiple' . $cellGroup);
            }
        }
    }

}
