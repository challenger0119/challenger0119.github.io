```c
int i = 1;
int main(){
    int i = i; // int i 申明开始 后面的i和前面的i是同一个
}
```

```c
//二进制1的个数 本身数字减去1后和本身相与总是少一个1

int oneNumber(int number){
    int count = 0;
    while(number){
        count++;
        number = number & (number - 1);
    }
    return number;
}
```

