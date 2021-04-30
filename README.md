# TW

Personnal helper to write things on terminal with PHP.

## Available functions

```
TWConsole::write(string $str, $foreground_color = null, $background_color = null)
TWConsole::writeLn(string $str, $foreground_color = null, $background_color = null)
TWConsole::writeUnder(string $str, $foreground_color = null, $background_color = null)
TWConsole::writeSeparator(string $separator = '-', int $size = 60)
TWConsole::writeTable(array $header, array $lines, bool $with_separator = false)
```

## Available colors

### Foreground

```
'black', 'dark_gray', 'blue', 'light_blue', 'green', 'light_green',
'cyan', 'light_cyan', 'red', 'light_red', 'purple', 'light_purple',
'brown', 'yellow', 'light_gray', 'white',
```

### Background

```
'black', 'red', 'green', 'yellow', 'blue', 
'magenta', 'cyan', 'light_gray',
```