# Docker container for antlr

"ANTLR (ANother Tool for Language Recognition) is a powerful parser generator for reading, processing, executing, or translating structured text or binary files. It's widely used to build languages, tools, and frameworks. From a grammar, ANTLR generates a parser that can build and walk parse trees."

www.antlr.org

## Setup

Use the docker-antlr helper script to fire up the container conveniently. docker-antlr accepts the very same command line options as antlr itself. it bind-mounts all the necessary directories (directories of your input and output files) and calls antlr within the container. You can get docker-antlr form the git repository or copy it directly from the container's root directory.

```
docker create --name antlr petervaczi/antlr
sudo docker cp antlr:docker-antlr /usr/local/bin/
docker rm antlr
```

### Usage

Use the docker-antlr script exactly as you would use antlr itself.

```
docker-antlr <antlr-options>
```

### Task to do
1. how to parse php? [php grammar](https://github.com/antlr/grammars-v4/tree/master/php)


### Check Points

1. How to verify visitor & listener? (in Pyhon, use pdb)
	- listner logic for that language is generated after comipling .g4, for example, ${rule}Listener.js is auto generated.
	- With Listener you can neither control the flow of a listener nor return anything from its functions, while you can do both of them with a visitor. So if you need to control how the nodes of the AST are entered, or to gather information from several of them, you probably want to use a visitor.
2. experiment with a transversal tree, verifty its transversal
3. how is it made? (Latte lang)[https://github.com/wkgcass/Latte-lang]

### Reference

1. Keywords:

```
* EOF
* fragment??
```

2. Regex:

```

```

3. what is the differece between parser and lexer rule?
```
	- lexer rule seems more fundamental
	- A lexer takes the individual characters and transforms them in tokens, the atoms that the parser uses to create the logical structure.
	- each projects have two .g4, starting with "lexer grammar Sth" and "parser grammar Sth"
	- in tree diagram, parser rule is the parts to recognized, lexer is for parser to reach a entity
	- The lexer doesn't work on the input directly, and the parser doesn't even see the characters.
	-Note that lexers try to match the longest string possible for each token,
	meaning that input beginner would match only to rule ID, when there is ambiguity.
```