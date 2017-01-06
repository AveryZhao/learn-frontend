# learn-frontend WEB前端学习笔记
##第一课 HTML基础
![测试图片](https://github.com/kuju/learn-frontend/blob/master/ScreenImg/test.png?raw=true "Test Img")
`
     /**
     *  
     * @param $bonus_total 红包总额
     * @param $bonus_count 红包个数
     * @param $bonus_max 每个小红包的最大额
     * @param $bonus_min 每个小红包的最小额
     * @return 存放生成的每个小红包的值的一维数组
     */  
    function getBonus($bonus_total, $bonus_count, $bonus_max, $bonus_min) {  
        $result = array();  
 
        $average = $bonus_total / $bonus_count;  
 
        $a = $average - $bonus_min;  
        $b = $bonus_max - $bonus_min;  
 
        //  
        //这样的随机数的概率实际改变了，产生大数的可能性要比产生小数的概率要小。  
        //这样就实现了大部分红包的值在平均数附近。大红包和小红包比较少。  
        $range1 = sqr($average - $bonus_min);  
        $range2 = sqr($bonus_max - $average);  
 
        for ($i = 0; $i < $bonus_count; $i++) {  
            //因为小红包的数量通常是要比大红包的数量要多的，因为这里的概率要调换过来。  
            //当随机数>平均值，则产生小红包  
            //当随机数<平均值，则产生大红包  
            if (rand($bonus_min, $bonus_max) > $average) {  
                // 在平均线上减钱  
                $temp = $bonus_min + xRandom($bonus_min, $average);  
                $result[$i] = $temp;  
                $bonus_total -= $temp;  
            } else {  
                // 在平均线上加钱  
                $temp = $bonus_max - xRandom($average, $bonus_max);  
                $result[$i] = $temp;  
                $bonus_total -= $temp;  
            }  
        }  
        // 如果还有余钱，则尝试加到小红包里，如果加不进去，则尝试下一个。  
        while ($bonus_total > 0) {  
            for ($i = 0; $i < $bonus_count; $i++) {  
                if ($bonus_total > 0 && $result[$i] < $bonus_max) {  
                    $result[$i]++;  
                    $bonus_total--;  
                }  
            }  
        }  
        // 如果钱是负数了，还得从已生成的小红包中抽取回来  
        while ($bonus_total < 0) {  
            for ($i = 0; $i < $bonus_count; $i++) {  
                if ($bonus_total < 0 && $result[$i] > $bonus_min) {  
                    $result[$i]--;  
                    $bonus_total++;  
                }  
            }  
        }  
        return $result;  
    }
`
