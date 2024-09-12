# Quick Find/Select All
```Cmd+Shift+L```

# Run current Python file with Cmd+Enter
keybindings.json
```json
[
  {
    "key": "cmd+enter",
    "command": "python.execInTerminal"
  }
]
```

# Cmd+Shift+P > Preferences Open Keyboard Shortcut (JSON)
```json
[
    {
        "key": "cmd+b",
        "command": "workbench.action.terminal.sendSequence",
        "args": {
            "text": "./create_html\u000D"  // Voert het huidige open bestand uit
        },
        "when": "editorTextFocus"
    },
]
```
