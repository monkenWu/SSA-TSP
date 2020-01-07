<?php
class SimulatedAnnealing{
    //地圖，陣列方式計算
    private $map;
    //規模
	private $n;
    //初始溫度
    private $T;
    //每個溫度下，達到降溫狀態的迭代次數
    private $L;
    //降溫常數：在每一個溫度下，實驗足夠多的轉移次數。小於卻接近於1的常數，λ通常為0.8~0.99之間。
    private $l;
    //降溫的中止條件，相當於將溫到常溫
    private $ord_temp;
    //輸出結果
	private $path;
	//最終結果
	private $ans;
	
	public function __construct($_map,$_n,$_T,$_L,$_l){
		$this->map = $_map;
		$this->n = $_n;
		$this->T = $_T;
		$this->L = $_L;
		$this->l = $_l;
		$this->ord_temp = 1;
	}
	
	function randomFloat($min = 0, $max = 1) {
		return $min + mt_rand() / mt_getrandmax() * ($max - $min);
	}

	public function getAns(){
		return [
			"ans" => $this->ans,
			"cost" => $this->cost($this->path)
		];
	}
	
	public function setAns(){
		$ans = "";
		$count = count($this->path)-1;
		foreach($this->path as $key => $value){
			$ans .= $value;
			if($count != $key) $ans .= "->";
		}
		$this->ans = $ans;
	}
	
	//第二個元素向右隨機移動1至n-2位
	public function new_S($_S){
		$temp_S = $_S;
		$shift = rand(1,$this->n-2);
		
		$ts = $temp_S[1];
		$temp_S[1] = $temp_S[1+$shift];
		$temp_S[1+$shift] = $ts;
		
		return $temp_S;
	}
	
	public function cost($_S){
		$_cost = 0;
		for($i=0;$i<$this->n-1;$i++)
		{
			$_cost += $this->map[$_S[$i]][$_S[$i+1]];
		}
		$_cost += $this->map[$_S[$i]][0];
		return $_cost;
	}
	
	public function calculate(){
		//初始解
		$S = array();
		
		for($i=0;$i<$this->n;$i++)
		{
			$S[$i] = $i;
		}
		$S[] = 0;
		$this->path = $S;
		//進入降溫階段，初始溫度為Ｔ
		$t = $this->T;
		while($t > $this->ord_temp){
			for($i=0;$i<$this->L;$i++){ 
				//產生新的解
				$S1 = $this->new_S($S);
				//判斷新解的價值
				$K = $this->cost($S1) - $this->cost($S);
				if($K < 0){
					$S = $S1;
					if($this->cost($S) < $this->cost($this->path))
						$this->path = $S;
				}else{
					//不好的解根據 Metropolis 準則接受
					if($this->randomFloat(0,1) < exp(-$K/$t)){
						$S = $S1;
					}
				}
			}
			/**
			 * 如果連續幾次降溫能量不變作為終止循環條件
			 * 本例按照降溫到常溫終止，不計算是否能量不變，需要初始值溫度給定的夠好
			 */
			$t = $t * $this->l;
		}
		//输出结果
		$this->setAns();
	}
}
?>