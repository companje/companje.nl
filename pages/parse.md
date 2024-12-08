---
title: Expression Parser
---

work in progress

```python
# Expression Parser - Bauer-Samelson algorithm as described in BYTE magazine 1976-02
# Python implementation: Rick Companje, 30 July 2022
# usage: main("20+4*(5-(6-3))/8")

def precedence_check(operators,c):
    if not operators or operators[-1] == '(':
        return True
    else:
        order = "^*/+-"
        return order.find(operators[-1]) > order.find(c)

def unstack(operators, operands):
    op = operators.pop()
    b = operands.pop()
    a = operands.pop()
    if op=="^":
      operands.append(a**b)
    elif op=="*":
      operands.append(a*b)
    elif op=="/":
      operands.append(int(a/b))
    elif op=="+":
      operands.append(a+b)
    elif op=="-":
      operands.append(a-b)


def parse(s, vars={}):
    operands = []
    operators = []
    pc = "" #prev char

    for c in s:
        if c==")":
            while operators[-1]!="(":
                unstack(operators,operands)
            operators.pop() #remove (
        if c.isnumeric():
            if pc.isnumeric():
                operands[-1] = int(str(operands[-1])+c)
            else:
                operands.append(int(c))
        elif c=="(":
            operators.append(c)
        elif c in "^+*/-":
            while not precedence_check(operators,c):
                unstack(operators,operands)
            operators.append(c)

        # print(c,"\t",operands,"\t",operators)
        pc=c # prev char for multidigit numbers

    while operators:
        # print(operators,operands)
        unstack(operators,operands)

    return operands[-1]

vars = {
    "t": 10,
    "i": 85,
    "x": 5,
    "y": 5
}

print(parse("t+4*(5-(6-3))/8"))
```
