
all:
	$(MAKE) -C nastroje
	php nastroje/vytvorit-dokumenty.php --lang=cz

en:
	$(MAKE) -C nastroje
	php nastroje/vytvorit-dokumenty.php --lang=en
