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
1. what is the differece between parser and lexer rule?
	- lexer rule seems more fundamental
	- A lexer takes the individual characters and transforms them in tokens, the atoms that the parser uses to create the logical structure.
2. How to verify visitor & listener? (in Pyhon, use pdb)
3. experiment with a transversal tree, verifty its transversal