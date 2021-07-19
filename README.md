# Where

Rescued from the ashes of my early days in programming and thankful I found it. Currently process a where array into a string using and and or to seperate. The thing here is that this package applies the parenthesis to the statement so where:

```
title=:title or modtime=:modtime or createtime=:createtime or id=:id
```

should be:

```
title=:title or ( modtime:modtime or createtime=:createtime ) or id=:id
```

If this is indeed the case ( what the string should be ), then first breaks and the second doesn't. Hence this package.

Right now it just handles comparison expressions.
