<?php namespace Bookrr\Store\Traits;

use Illuminate\Support\Arr;
use Carbon\Carbon;

trait Widgets{

    public function NewFormWidget($options)
    {
        $config = $this->makeConfig($options['config']);

        $config->model = $options['model'];

        $config->alias = $options['alias'];

        $config->arrayName = $options['arrayName'];

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);

        $widget->bindToController();

        return $widget;
    }

    public function NewListWidget()
    {
        $config = $this->makeConfig('$/bookrr/store/controllers/carbooking/config_list.yaml');
        $columnConfig = $this->makeConfig($config->list);
        $columnConfig->model = new CarBooking;
        $columnConfig->recordUrl = 'bookrr/store/carbooking/update/:id';
        $widget = $this->makeWidget('Backend\Widgets\Lists',$columnConfig);
        $widget->bindToController();

        if (isset($config->toolbar)) {
            $toolbarConfig = $this->makeConfig($config->toolbar);
            $toolbarConfig->alias = $widget->alias . 'Toolbar';
            $toolbarWidget = $this->makeWidget('Backend\Widgets\Toolbar', $toolbarConfig);
            $toolbarWidget->bindToController();
            $toolbarWidget->cssClasses[] = 'list-header';

            /*
             * Link the Search Widget to the List Widget
             */
            if ($searchWidget = $toolbarWidget->getSearchWidget()) {
                $searchWidget->bindEvent('search.submit', function () use ($widget, $searchWidget) {
                    $widget->setSearchTerm($searchWidget->getActiveTerm());
                    return $widget->onRefresh();
                });

                $widget->setSearchOptions([
                    'mode' => $searchWidget->mode,
                    'scope' => $searchWidget->scope,
                ]);

                // Find predefined search term
                $widget->setSearchTerm($searchWidget->getActiveTerm());
            }

            // $widget->toolbarWidgets['list'] = $toolbarWidget;
        }

        return $widget;
    }

}