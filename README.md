# learn-frontend WEB前端学习笔记
##第一课 HTML基础
![测试图片](https://github.com/kuju/learn-frontend/blob/master/ScreenImg/test.png?raw=true "Test Img")

`代码区块`


```php
<?php
    /**
    * 生产min和max之间的随机数，但是概率不是平均的，从min到max方向概率逐渐加大。
    * 先平方，然后产生一个平方值范围内的随机数，再开方，这样就产生了一种“膨胀”再“收缩”的效果。
    */  
    function xRandom($bonus_min,$bonus_max){
        $sqr = intval(sqr($bonus_max-$bonus_min));
        $rand_num = rand(0, ($sqr-1));
        return intval(sqrt($rand_num));
    }
    
```
    
# 其他内容
