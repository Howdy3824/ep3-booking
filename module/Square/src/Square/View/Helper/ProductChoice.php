<?php

namespace Square\View\Helper;

use Square\Entity\Square;
use Square\Manager\SquareProductManager;
use Zend\View\Helper\AbstractHelper;

class ProductChoice extends AbstractHelper
{

    protected $squareProductManager;

    public function __construct(SquareProductManager $squareProductManager)
    {
        $this->squareProductManager = $squareProductManager;
    }

    public function __invoke(Square $square)
    {
        $products = $this->squareProductManager->getBySquare($square);

        if (! $products) {
            return null;
        }

        $view = $this->getView();
        $html = '';

        $html .= '<table class="default-table">';

        foreach ($products as $product) {
            $html .= '<tr>';

            $spid = $product->need('spid');

            $quantityOptions = explode(',', $product->need('options'));

            $html .= '<td>';
            $html .= '<select id="sb-product-' . $spid . '" class="sb-product" data-spid="' . $spid . '">';

            $html .= '<option value="0" selected="selected">' . $view->t('None') . '</option>';

            foreach ($quantityOptions as $quantityOption) {
                $html .= '<option value="' . $quantityOption . '">' . $quantityOption . '</option>';
            }

            $html .= '</select>';
            $html .= '</td>';

            $html .= '<td style="max-width: 384px; padding-left: 16px; border-left: solid 1px #CCC; border-right: solid 1px #CCC;">';
            $html .= '<div class="large-text"><label for="sb-product-' . $spid . '">' . $product->need('name') . '</label></div>';

            if ($product->get('description')) {
                $html .= '<div class="separator-tiny"></div>';
                $html .= '<div>' . $product->get('description') . '</div>';
            }

            $html .= '</td>';

            $html .= '<td style="padding-left: 16px;">';
            $html .= $view->priceFormat($product->need('price'), $product->need('rate'), $product->need('gross'), null, null, 'per item');
            $html .= '</td>';

            $html .= '</tr>';
        }

        $html .= '</table>';

        return $html;
    }

}