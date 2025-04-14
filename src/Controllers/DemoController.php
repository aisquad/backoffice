<?php
namespace Backoffice\Controllers;

use Backoffice\Core\Controller;
use Backoffice\Core\Sql\DataLoader;

class DemoController extends Controller
{
    public function list()
    {
        $dataLoader = new DataLoader();
        $towns = $dataLoader->getData('towns');
        $budget = $dataLoader->getData('budget');
        $topSelling = $dataLoader->getData('top_selling');
        $recentActivities = $dataLoader->getData('recent_activities');
        $recentSales = $dataLoader->getData('recent_sales');
        $reports = $dataLoader->getData('reports_data');
        $trafficSources = $dataLoader->getData('traffic_sources');
        $newsUpdates = $dataLoader->getData('news_updates');
        $pages = $dataLoader->getData('pages');
        $data = [
            "towns" => $towns,
            "budget" => $budget,
            "recent_activities" => $recentActivities,
            "recent_sales" => $recentSales,
            "top_selling" => $topSelling,
            "reports" => $reports,
            "traffic_sources" => $trafficSources,
            "news_updates" => $newsUpdates,
            "pages" => $pages
        ];
        return [
            'status' => 200,
            'body' => [
                'success' => true,
                'data' => $data
            ]
        ];
    }
}