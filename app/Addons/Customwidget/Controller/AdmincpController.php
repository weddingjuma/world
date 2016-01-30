<?php
namespace App\Addons\Customwidget\Controller;
use App\Addons\Customwidget\Classes\CustomWidgetRepository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class AdmincpController extends \App\Controllers\Admincp\AdmincpController
{
    public function __construct(CustomWidgetRepository $customWidgetRepository)
    {
        $this->widgetRepository = $customWidgetRepository;
        parent::__construct();
        $this->activePage('custom-widgets');
    }

    public function index()
    {
        $this->setTitle('Custom Widgets');
        return $this->theme->view('customwidget::admincp.index', ['widgets' => $this->widgetRepository->getAll()])->render();
    }

    public function add()
    {
        $this->setTitle('Add New Widget');
        $message = null;

        if ($val = \Input::get('val')) {
                $widget = $this->widgetRepository->add($val);
            if ($widget) {
                //direct to the list page
                return \Redirect::route('admincp-custom-widgets');
            }

            $message = "Failed to add the new widget due to invalid widget content";
        }

        return $this->theme->view('customwidget::admincp.add', ['message' => $message])->render();
    }

    public function edit($id)
    {
        $this->setTitle('Edit Widget');
        $message = null;

        $widget = $this->widgetRepository->findById($id);

        if ($val = \Input::get('val')) {
            $this->widgetRepository->save($val, $widget);
            return \Redirect::route('admincp-custom-widgets');

        }
        if (!$widget) return \Redirect::route('admincp-custom-widgets');

        return $this->theme->view('customwidget::admincp.edit', ['message' => $message, 'widget' => $widget])->render();
    }

    public function delete($id)
    {
        $this->widgetRepository->delete($id);
        return \Redirect::route('admincp-custom-widgets');
    }
}