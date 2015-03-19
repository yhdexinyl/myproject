<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/3/6 0006
 * Time: 下午 3:37
 */
abstract class Model {
    protected $db; //不能够是private. 否则子类无法访问该db
    /**
     * 用来保存错误信息
     * @var
     */
    public $error_info;
    protected  $table_name = '';  //解决XXXModel的名字和表名不一样的问题
    protected  $fields;   //存放操作的表中的所有列

    public  function __construct(){
        //构造函数在创建对象时就执行
        $this->initDB();
        $this->initField();
    }
    public function initDB(){
//        require FRAMEWORK_PATH.'DB.class.php';
//        $GLOBALS 中存放都是全局变量
        $this->db = DB::getInstance($GLOBALS['config']['DB']);
    }

    /**
     * 将操作的表中的列查询出来保存到$this->fields中
     *
     * $this->fields = array('pk'=>主键,'name','parent_id');
     */
    public function initField(){
            $sql = "desc {$this->table()}";
            $rows = $this->db->fetchAll($sql);
            foreach($rows as $row){
                if($row['Key']=='PRI'){
                    $this->fields['PK'] = $row['Field'];
                }else{
                    $this->fields[] = $row['Field'];
                }
            }
    }


    public function table(){
        $table_prefix = $GLOBALS['config']['DB']['prefix'];
        if(empty($this->table_name)){
            $this->table_name = $this->parse_name(strstr(get_class($this),'Model',true),0);  //$this是子类对象,  get_class($this)得到的是子类的名字
        }
        return '`'.$table_prefix.$this->table_name.'`';  //it_category    order
    }


    /**
     * 将名字作为不同格式
     *
     * 例如:
     * parse_name('Admin',0)   返回值为: admin
     * parse_name('admin_manager',1)   返回值为: AdminManager
     *
     * @param $name
     * @param int $type
     * @return string
     */
   public function parse_name($name, $type=0) {
        if ($type) {
            return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function($match){return strtoupper($match[1]);}, $name));
        } else {
            return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
        }
    }


    /**
     * 得到所有表中的数据
     *
     * 例如:
     * getAll('name like '%张三%'');
     */
    public function getAll($condition = ''){
        $sql = "select * from {$this->table()}";
        if(!empty($condition)){
            $sql.=' where '.$condition;
        }
        return $this->db->fetchAll($sql);
    }


    /**
     * 根据一个条件得到一行数据
     * @param string $condition
     * @return mixed
     */
    public function getRow($condition = ''){
        $sql = "select * from {$this->table()}";
        if(!empty($condition)){
            $sql.=' where '.$condition;
        }
        return $this->db->fetchRow($sql);
    }


    /**
     * 根据主键得到一行数据
     */
    public function getByPK($pk){
        $sql  = "select * from {$this->table()} where {$this->fields['PK']} = $pk";
        return $this->db->fetchRow($sql);
    }


    /**
     * 根据主键的值删除一行数据
     */
    public function deleteByPK($pk){
        $sql = "delete from {$this->table()} where {$this->fields['PK']} = $pk";
        return $this->db->query($sql);
    }

    /**
     * 将data中的数据插入到对应的表中.
     * 前提:  data是一个关联数组, 键和数据库表中的字段对应...
     * @param $data
     *
     * $data = array('name'=>'手机','intro'=>'精品手机','parent_id'=>1);
     *
     * 使用类似与上面的data值拼出下面的sql
     *  insert into category(`name`,`intro`,parent_id)  values('手机','精品手机',1)
     */
    public function insertData($data){

        //将$data中的数据 和 表中的列 对比... data中数据的键不在表列(this->feilds)中.. 说明就将该键从data中删除
        $this->ignoreFields($data);

        $sql  = "insert into ".$this->table().'(';
        $keys = array_keys($data);
        //>>1.根据键拼表名后面的列名
            $keys = array_map(function($key){ return '`'.$key.'`'; },$keys);
            $keys = implode(',',$keys);
            $sql.=$keys.') values (';

         $values = array_values($data);
        //>>2.根据值拼values后面的值
        $values = array_map(function($value){ return "'".$value."'"; },$values);
        $values = implode(',',$values);
        $sql.=$values.')';
        return $this->db->query($sql);
    }

    /**
     * 忽略不合法的数据.   忽略data中键不在数据表中的数据
     * @param $data
     */
    private function ignoreFields(&$data){
        $keys = array_keys($data);
        foreach($keys as $key){
            if(!in_array($key,$this->fields)){  //检测data中的键是否在$this->fields中存在
                unset($data[$key]);
            }
        }
    }

    /**
     * @param $data  是一个关联数组
     *
     * $data = array('id'=>'2','name'=>'手机','intro'=>'精品手机','parent_id'=>1);
     *
     * 使用类似与上面的data值拼出下面的sql
     *  update category set `name`='手机',`intro`='精品手机',`parent_id`='1'  where `id`='2'
     */
    public function updateData($data,$condition=''){
        //update category set `name`='手机',`intro`= '精品手机',`parent_id`='1',
         $this->ignoreFields($data);

        $sql = "update ".$this->table().' set ';
        foreach($data as $key=>$value){
            if($key!=$this->fields['PK']){  //排除主键
                $sql.= '`'.$key."`='".$value."',";
            }
        }
        $sql = rtrim($sql,',');  //删除右边的逗号

        if(!empty($condition)){
            $sql.'  where '.$condition;
        }elseif(array_key_exists($this->fields['PK'],$data)){
            $sql.=" where `".$this->fields['PK'].'`='.$data[$this->fields['PK']];
        }else{
            return false;
        }
        return $this->db->query($sql);
    }

    /**
     * 根据条件统计条数
     * @param string $condition
     */
    public function count($condition=''){
        $sql = "select count(*) as count from ".$this->table();
        if(!empty($condition)){
            $sql.=" where ".$condition;
        }
        return $this->db->fetchColumn($sql);
    }


    /**
     * 得到最后产生的id
     */
    public function get_last_id(){
        return mysql_insert_id();
    }



    /**
     * 返回值中包含  两个内容
     * 1. 当前列表的数据
     * 2. 总条数
     * @param $page
     * @param  $pageSize 用户传递过来的每页显示多少条
     */
    public function getPageResult($page,$pageSize){
        //>>1.得到当前页的数据列表
        $start = ($page-1)*$pageSize;
        $rows = $this->getAll("1=1 limit $start,$pageSize");//  select * from it_goods where  1=1  limit $start,$pageSize
        //>>2.得到总条数
        $count = $this->count();
        return  array('rows'=>$rows,'count'=>$count);
    }

}