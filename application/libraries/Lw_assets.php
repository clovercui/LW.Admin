<?php
class Lw_assets {

    protected $CI;
    protected $assets;
    protected $homeAssets;
    protected $loginAssets;
    protected $pageAssets;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->assets = [
            'cssList' => [],
            'jsList' => [],
            'pageCss' => [],
            'pageJs'=>[]
        ];
        $this->homeAssets = ['spop', 'sweetalert'];
        $this->loginAssets = ['spop', 'sweetalert','validation', 'jQueryForm'];
        $this->pageAssets = ['spop', 'sweetalert','validation', 'jQueryForm'];
    }

    public function getHomeAssets($dataList = [])
    {
        $dataList = array_merge($dataList, $this->homeAssets);
        return $this->getAssetsFromBase($dataList);
    }
    public function getLoginAssets($dataList = [])
    {
        $dataList = array_merge($dataList, $this->loginAssets);
        return $this->getAssetsFromBase($dataList);
    }

    public function getPageAssets($dataList = [])
    {
        $dataList = array_merge($dataList, $this->pageAssets);
        return $this->getAssetsFromBase($dataList);
    }


    private function getAssetsFromBase($dataList)
    {
        foreach($dataList as $data){
            $base = $this->assetsBase();
            if(!isset($base[$data])){
                continue;
            }
            if(isset($base[$data]['css'])){
                foreach($base[$data]['css'] as $css){
                    array_push($this->assets['cssList'],$css);
                }
            }
            if(isset($base[$data]['js'])){
                foreach($base[$data]['js'] as $js){
                    array_push($this->assets['jsList'],$js);
                }
            }
        }
        return $this->assets;
    }

    private function assetsBase(){
        $base = [];
        //按钮加载功能：可做全局部署其实
        $base['ladda'] = [
            'css' => [
                base_url('assets/plugins/ladda/ladda-themeless.min.css?v='.VERSION),
            ],
            'js'=>[
                base_url('assets/plugins/ladda/spin.min.js?v='.VERSION),
                base_url('assets/plugins/ladda/ladda.min.js?v='.VERSION),
            ]
        ];
        // 提示组件
        $base['notific8'] = [
            'css' => [
                base_url('assets/plugins/jquery-notific8/jquery.notific8.min.css?v='.VERSION),
            ],
            'js' => [
                base_url('assets/plugins/jquery-notific8/jquery.notific8.min.js?v='.VERSION),
            ]
        ];
        // JS 树控件
        $base['jstree'] = [
            'css' => [
                base_url('assets/plugins/jstree/dist/themes/default/style.min.css?v='.VERSION),
            ],
            'js' => [
                base_url('assets/plugins/jstree/dist/jstree.min.js?v='.VERSION),
            ]
        ];
        //表单验证组件
        $base['validation'] = [
            'js' => [
                base_url('assets/plugins/jQuery-validate/jquery.validate.min.js?v='.VERSION),
                base_url('assets/plugins/jQuery-validate/additional-methods.min.js?v='.VERSION),
            ]
        ];
        // sweetAlert 提示框
        $base['sweetalert'] = [
            'js' => [
                'assets/plugins/sweetalert/sweetalert.min.js?v='.VERSION,
            ]
        ];

        // spop 控件 用于 提示
        $base['spop'] = [
            'css' => [
                'assets/plugins/spop/spop.min.css'
            ],
            'js' => [
                'assets/plugins/spop/spop.min.js'
            ]
        ];

        // 表单提交
        $base['jQueryForm'] = [
            'js' => [
                'assets/plugins/jQuery_form/jQuery-form.js'
            ]
        ];

        // JS 树控件
        $base['jstree'] = [
            'css' => [
                'assets/plugins/jstree/dist/themes/default/style.min.css?v='.VERSION
            ],
            'js' => [
                'assets/plugins/jstree/dist/jstree.min.js?v='.VERSION
            ]
        ];

        // 日期控件
        $base['datetime'] = [
            'js' => [
                'assets/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js',
                'assets/plugins/datetimepicker/js/bootstrap-datetimepicker.zh-CN.js'
            ],
            'css' => [
                'assets/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css'
            ]
        ];

        return $base;
    }
}
