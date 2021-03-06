# qTranslate

```php
$q = new \JSONplus\qTranslate();
```

## Syntax of qTranslate texts
A translation starts for a section &mdash; a block.

```
[:en]English[:nl]Nederlands[:es]Espagnol[:]
```

With `[:en]` you start the English-variant of this block. By `[:nl]` or `[:es]` you start the other-language-variants. With `[:]` the current block will be closed.

This structure provides blocks which are translated, an blocks which are literal.

Added flags can be insert postfixed to the language-code like `[:en*~]`. Use the flag:
- `*` to assign the *default* language.
- `!` to assign this language to be used in this block. All other language-alternates are being ignored.
- `?` notes this translation is a *concept*.
- `~` notes this translation was generated by a tool like [Google Translate](https://translate.google.com/).

You could also use references for *gettext* or other `::pointer()`-methods by: `[#reference]` (shortest) or `[:][:#]reference[:]` (where the `[:]` can be optional). A reference has to comply to the pattern `[a-zA-Z0-9_\-]{1,32}`.

## History
More then 5 years ago I used an [Wordpress](https://wordpress.org/)-extension for i18n. It was called *qTranslate*. These days it has become discontinued, even its successors. But I really liked its simplicity, of its syntax. By using `[:en]English text[:]` or `<!--:en-->English text<!--:-->` it could denote which *blocks* are language specific (and having multiple translated blocks) or non-specific.
