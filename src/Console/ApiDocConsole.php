<?php

namespace Console;

use FastD\Routing\Route;
use Helper\DocParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * api文档生成器，格式参照SearchController
 * Class ApiDocConsole
 * @package Console
 */
class ApiDocConsole extends Command
{
    // 要生成的控制器
    public $controller;

    // 输出的md路径
    public $output;

    public $file;

    public function configure()
    {
        $this->setName('ant-fd:gen-doc')
            ->setDescription('生成对应控制器的markdown文档')
            ->addOption(
                'controller',
                'c',
                InputOption::VALUE_REQUIRED,
                '要生成文档的控制器'
            )
            ->addOption(
                'output',
                'o',
                InputOption::VALUE_OPTIONAL,
                '输出md的文件夹路径(请用绝对路径),默认放在doc目录'
            )
            ->addOption('filename', 'f', InputOption::VALUE_OPTIONAL);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->controller = ucfirst($input->getOption('controller')) . 'Controller';
        $this->output = $input->getOption('output') ? $input->getOption('output') : './doc';
        $this->file = $input->getOption('filename') ? $input->getOption('filename') : '接口文档';

        $this->_genMd($this->_genDoc());
    }

    private function _genDoc()
    {
        $routeList = route()->aliasMap;
        $doc = [];

        foreach ($routeList as $value) {
            foreach ($value as $v) {
                /* @var Route $v */
                $data = [
                    'url' => $v->getName(),
                    'method' => $v->getMethod(),
                ];

                list($callbackController, $callbackMethod) = explode('@', $v->getCallback());

                if (strpos($callbackController, $this->controller) === false) {
                    continue;
                }

                $reflect = new \ReflectionClass($callbackController);
                $method = $reflect->getMethod($callbackMethod);

                $docParser = new DocParser();
                $info = $docParser->parse($method->getDocComment());

                if (!empty($info['name'])) {
                    $data['name'] = $info['name'];
                }

                if (!empty($info['apiParam'])) {
                    $data['apiParam'] = [];

                    if (!is_array($info['apiParam'])) {
                        list($name, $type, $text, $must) = explode('|', $info['apiParam']);
                        $data['apiParam'][] = [
                            'name' => $name,
                            'type' => $type,
                            'text' => $text,
                            'must' => $must
                        ];
                    } else {
                        foreach ($info['apiParam'] as $api) {
                            list($name, $type, $text, $must) = explode('|', $api);
                            $data['apiParam'][] = [
                                'name' => $name,
                                'type' => $type,
                                'text' => $text,
                                'must' => $must
                            ];
                        }
                    }
                }

                if (!empty($info['returnParam'])) {
                    $data['returnParam'] = [];

                    if (!is_array($info['returnParam'])) {
                        list($name, $type, $text) = explode('|', $info['returnParam']);
                        $data['returnParam'][] = [
                            'name' => $name,
                            'type' => $type,
                            'text' => $text,
                        ];
                    } else {
                        foreach ($info['returnParam'] as $return) {
                            list($name, $type, $text) = explode('|', $return);
                            $data['returnParam'][] = [
                                'name' => $name,
                                'type' => $type,
                                'text' => $text,
                            ];
                        }
                    }
                }

                $doc[] = $data;
            }
        }

        return $doc;
    }

    private function _genMd($doc)
    {
        if (!$doc) {
            return ;
        }

        $text = '';
        foreach ($doc as $value) {
            $text .= "## {$value['name']}";
            $text .= PHP_EOL;
            $text .= PHP_EOL;
            $text .= "### url";
            $text .= PHP_EOL;
            $text .= "`{$value['url']}`";
            $text .= PHP_EOL;
            $text .= PHP_EOL;
            $text .= "### 请求方法";
            $text .= PHP_EOL;
            $text .= "`{$value['method']}`";
            $text .= PHP_EOL;
            $text .= PHP_EOL;
            $text .= "### 请求参数";
            $text .= PHP_EOL;
            $text .= "| 参数名 | 参数类型 | 说明 | 是否必须 |";
            $text .= PHP_EOL;
            $text .= "| :-----: | :-----: | :-----: | :-----: |";
            $text .= PHP_EOL;

            if (isset($value['apiParam'])) {
                $paramStr = '';
                foreach ($value['apiParam'] as $param) {
                    $paramStr .= "| {$param['name']} | {$param['type']} | {$param['text']} | {$param['must']} |";
                    $paramStr .= PHP_EOL;
                }
                $paramStr .= PHP_EOL;
                $text .= $paramStr;
            }

            $text .= PHP_EOL;
            $text .= "### 响应参数";
            $text .= PHP_EOL;
            $text .= "| 参数名 | 参数类型 | 说明 |";
            $text .= PHP_EOL;
            $text .= "| :-----: | :-----: | :-----: |";
            $text .= PHP_EOL;

            if (isset($value['returnParam'])) {
                $paramStr = '';
                foreach ($value['returnParam'] as $param) {
                    $paramStr .= "| {$param['name']} | {$param['type']} | {$param['text']} |";
                    $paramStr .= PHP_EOL;
                }
                $paramStr .= PHP_EOL;
                $text .= $paramStr;
            }
        }

        if (!is_dir($this->output)) {
            mkdir($this->output, 0755);
        }

        file_put_contents($this->output . '/' . $this->file . '.md', $text);
    }
}
