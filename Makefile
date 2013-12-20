help:

	@echo "Targets:"
	@echo "  install - install mage-repl"
	@echo "  install-composer - install composer"
	@echo "  install-dependencies - install/update all vendor libraries using composer"
	@echo ""
	@exit 0

install:

	@make install-dependencies
	@chmod +x mage-repl.php
	@ln -s $(CURDIR)/mage-repl.php $$HOME/bin/mage-repl

install-composer:

	@if [ ! -d $$HOME/bin ]; then mkdir $$HOME/bin; fi
	@if [ ! -f $$HOME/bin/composer.phar ]; then curl -sS https://getcomposer.org/installer | php -- --install-dir=$$HOME/bin/; fi
	@ln -s $$HOME/bin/composer.phar $$HOME/bin/composer

install-dependencies:

	@make install-composer
	@composer install

.PHONY: help
