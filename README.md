# Gumbo Millennium Themosis

Dit is de nieuwe website.

## Development

Gebruik de *Docker* om te werken, of installeer het lokaal en stel de
environment variabele `GUMBO_ENV` op `local` of `development`

Een voorbeeldje voor nginx:

```nginx
server{
  # ...

  fastcgi_param GUMBO_ENV development;

  # ...
}
```
