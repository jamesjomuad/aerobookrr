<?php namespace Aeroparks\General\Traits;

use Illuminate\Support\Arr;
use Carbon\Carbon;

trait Widgets{

    public function FormWidget($options)
    {
        $config = $this->makeConfig($options['config']);

        $config->model = $options['model'];

        $config->alias = $options['alias'];

        $config->arrayName = $options['arrayName'];

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);

        $widget->bindToController();

        return $widget;
    }

    public function ListWidget($config)
    {
        $config = $this->makeConfig($config);

        $ListConfig = $this->makeConfig($config->list);

        $ListConfig->model = new $config->modelClass();

        $mergeConfig = (object)collect($ListConfig)->merge($config)->all();

        $widget = $this->makeWidget('Backend\Widgets\Lists',$mergeConfig);

        $widget->bindToController();

        return $widget;
    }

    public function ToolbarWidget($widget,$config)
    {
        $config = $this->makeConfig($config);
        
        $toolbarConfig = $this->makeConfig($config->toolbar);

        $toolbarConfig->alias = 'listToolbarSearch';

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

        return $toolbarWidget;
    }

    protected function FilterWidget () {

        # Step 3. The filter part, we must define the config, really similar to the Product list widget config
        # Filter configuration file
        $filterConfig = $this->makeConfig('$/redmarlin/shopclerk/models/product/filter_relation.yaml');
    
        # Use Filter widgets to make the widget and bind it to the controller
        $filterWidget = $this->makeWidget('Backend\Widgets\Filter', $filterConfig);
        $filterWidget->bindToController();
    
        # We need to bind to filter.update event in order to refresh the list after selecting 
        # the desired filters.
        $filterWidget->bindEvent('filter.update', function () use ($widget_product, $filterWidget) {
                return $widget_product->onRefresh();
            });
    
        #Finally we are attaching The Filter widget to the Product widget.
        $widget_product->addFilter([$filterWidget, 'applyAllScopesToQuery']);
    
        $this->productFilterWidget = $filterWidget;
    
        # Dont forget to bind the whole thing to the controller
        $widget_product->bindToController();
    
        #Return the prepared widget object
        return $widget_product;
    
    }

}