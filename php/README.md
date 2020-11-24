###Reference:

1. https://github.com/antlr/grammars-v4/tree/master/php
  - for java language, need to copy the Java/PhpBaseLexer.java

##Run steps:        
```
1. antlr PhpLexer.g4 
2. antlr PhpParser.g4
3. javac Php*.java
4. grun Php htmlDocument -tokens sample.php > results 
```

