#!/bin/bash

###############################################################################
# Script de vérification des prérequis pour CMS 7.1.x
# Système validé : Ubuntu 18.04 LTS
# Date : 2026-02-04
###############################################################################

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Compteurs
PASSED=0
FAILED=0
WARNINGS=0

# Fonction d'affichage
print_header() {
    echo -e "\n${BLUE}========================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}========================================${NC}"
}

print_check() {
    echo -e "\n${YELLOW}Vérification: $1${NC}"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
    ((PASSED++))
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
    ((FAILED++))
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
    ((WARNINGS++))
}

print_info() {
    echo -e "  $1"
}

# Fonction pour comparer les versions
version_ge() {
    test "$(printf '%s\n' "$1" "$2" | sort -V | head -n 1)" = "$2"
}

###############################################################################
# DÉBUT DES VÉRIFICATIONS
###############################################################################

echo -e "${BLUE}"
echo "╔══════════════════════════════════════════════════════════════════╗"
echo "║     Script de vérification des prérequis CMS 7.1.x              ║"
echo "╚══════════════════════════════════════════════════════════════════╝"
echo -e "${NC}"

###############################################################################
# 1.1 - Gestionnaire des dépendances
###############################################################################
print_header "1.1 - Gestionnaire des dépendances"

print_check "Composer"
if command -v composer &> /dev/null; then
    COMPOSER_VERSION=$(composer --version 2>/dev/null | grep -oP '(?<=Composer version )\d+\.\d+\.\d+' || composer --version 2>/dev/null | grep -oP '\d+\.\d+\.\d+')
    COMPOSER_MAJOR=$(echo "$COMPOSER_VERSION" | cut -d. -f1)
    
    if [ "$COMPOSER_MAJOR" -ge 2 ]; then
        print_success "Composer installé - Version: $COMPOSER_VERSION (V2 requis)"
    else
        print_error "Composer V2 requis - Version actuelle: $COMPOSER_VERSION"
    fi
else
    print_error "Composer n'est pas installé"
    print_info "Installation: curl -sS https://getcomposer.org/installer | php && sudo mv composer.phar /usr/local/bin/composer"
fi

###############################################################################
# 1.2 - Système d'exploitation et PHP
###############################################################################
print_header "1.2 - Système d'exploitation et PHP"

print_check "Système d'exploitation"
if [ -f /etc/os-release ]; then
    . /etc/os-release
    print_info "Système: $NAME $VERSION"
    if [[ "$ID" == "ubuntu" ]]; then
        print_success "Ubuntu détecté (18.04 LTS recommandé)"
    else
        print_warning "Système non Ubuntu (Ubuntu 18.04 LTS recommandé)"
    fi
else
    print_warning "Impossible de déterminer le système d'exploitation"
fi

print_check "Version PHP"
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -r "echo PHP_VERSION;" 2>/dev/null)
    PHP_MAJOR_MINOR=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;" 2>/dev/null)
    
    print_info "Version installée: PHP $PHP_VERSION"
    
    if version_ge "$PHP_MAJOR_MINOR" "7.3"; then
        print_success "PHP >= 7.3 installé"
    else
        print_error "PHP >= 7.3 requis - Version actuelle: $PHP_VERSION"
    fi
else
    print_error "PHP n'est pas installé"
fi

###############################################################################
# 1.3 - Composants validés
###############################################################################
print_header "1.3 - Composants validés"

# Apache
print_check "Apache"
if command -v apache2 &> /dev/null; then
    APACHE_VERSION=$(apache2 -v 2>/dev/null | grep -oP '(?<=Apache/)\d+\.\d+\.\d+' | head -1)
    if [ -n "$APACHE_VERSION" ]; then
        print_success "Apache installé - Version: $APACHE_VERSION (2.4.29 recommandé)"
    else
        print_success "Apache installé"
    fi
else
    print_error "Apache n'est pas installé"
fi

# MySQL/MariaDB
print_check "MySQL/MariaDB"
if command -v mysql &> /dev/null; then
    MYSQL_VERSION=$(mysql --version 2>/dev/null | grep -oP '(?<=Distrib )\d+\.\d+\.\d+' || mysql --version 2>/dev/null | grep -oP '\d+\.\d+\.\d+-MariaDB')
    if [ -n "$MYSQL_VERSION" ]; then
        print_success "MySQL/MariaDB installé - Version: $MYSQL_VERSION"
    else
        print_success "MySQL/MariaDB installé"
    fi
else
    print_error "MySQL/MariaDB n'est pas installé"
fi

# ImageMagick
print_check "ImageMagick"
if command -v convert &> /dev/null; then
    IMAGEMAGICK_VERSION=$(convert -version 2>/dev/null | grep -oP '(?<=Version: ImageMagick )\d+\.\d+\.\d+-\d+' | head -1)
    CONVERT_PATH=$(which convert)
    
    if [ -n "$IMAGEMAGICK_VERSION" ]; then
        print_success "ImageMagick installé - Version: $IMAGEMAGICK_VERSION (6.9.7.4 recommandé)"
    else
        print_success "ImageMagick installé"
    fi
    
    if [ "$CONVERT_PATH" = "/usr/bin/convert" ] || [ -L "/usr/bin/convert" ]; then
        print_success "L'exécutable 'convert' est accessible sous /usr/bin/convert"
    else
        print_warning "L'exécutable 'convert' n'est pas à /usr/bin/convert (trouvé: $CONVERT_PATH)"
        print_info "Créer un lien symbolique: sudo ln -s $CONVERT_PATH /usr/bin/convert"
    fi
else
    print_error "ImageMagick (convert) n'est pas installé"
fi

# Catdoc
print_check "Catdoc"
if command -v catdoc &> /dev/null; then
    print_success "Catdoc installé (0.95-4.1 recommandé)"
else
    print_error "Catdoc n'est pas installé"
    print_info "Installation: sudo apt-get install catdoc"
fi

# Xls2csv
print_check "Xls2csv"
if command -v xls2csv &> /dev/null; then
    print_success "Xls2csv installé (inclus dans le package catdoc)"
else
    print_error "Xls2csv n'est pas installé"
fi

# Catppt
print_check "Catppt"
if command -v catppt &> /dev/null; then
    print_success "Catppt installé (inclus dans le package catdoc)"
else
    print_error "Catppt n'est pas installé"
fi

# Poppler (pdftotext)
print_check "Poppler (pdftotext)"
if command -v pdftotext &> /dev/null; then
    POPPLER_VERSION=$(pdftotext -v 2>&1 | grep -oP '(?<=version )\d+\.\d+\.\d+' | head -1)
    if [ -n "$POPPLER_VERSION" ]; then
        print_success "Poppler installé - Version: $POPPLER_VERSION (0.62.0-2 recommandé)"
    else
        print_success "Poppler (pdftotext) installé"
    fi
else
    print_error "Poppler (pdftotext) n'est pas installé"
    print_info "Installation: sudo apt-get install poppler-utils"
fi

###############################################################################
# 1.4 - Modules PHP validés
###############################################################################
print_header "1.4 - Modules PHP validés"

if command -v php &> /dev/null; then
    PHP_MODULES=(
        "bz2"
        "cli"
        "common"
        "curl"
        "fpm"
        "gd"
        "json"
        "ldap"
        "mbstring"
        "mysql"
        "opcache"
        "readline"
        "xml"
        "zip"
    )
    
    print_check "Modules PHP"
    MISSING_MODULES=()
    
    for module in "${PHP_MODULES[@]}"; do
        if php -m 2>/dev/null | grep -qi "^$module$"; then
            print_info "✓ php-$module"
        else
            print_info "✗ php-$module - MANQUANT"
            MISSING_MODULES+=("php7.3-$module")
        fi
    done
    
    if [ ${#MISSING_MODULES[@]} -eq 0 ]; then
        print_success "Tous les modules PHP requis sont installés"
    else
        print_error "${#MISSING_MODULES[@]} module(s) PHP manquant(s)"
        print_info "Installation: sudo apt-get install ${MISSING_MODULES[*]}"
    fi
else
    print_error "PHP n'est pas installé, impossible de vérifier les modules"
fi

###############################################################################
# 1.5 - Configuration PHP
###############################################################################
print_header "1.5 - Configuration PHP"

if command -v php &> /dev/null; then
    print_check "Configuration PHP"
    
    # short_open_tag
    SHORT_OPEN_TAG=$(php -r "echo ini_get('short_open_tag');" 2>/dev/null)
    if [ "$SHORT_OPEN_TAG" = "" ] || [ "$SHORT_OPEN_TAG" = "0" ]; then
        print_success "short_open_tag = Off"
    else
        print_error "short_open_tag doit être Off (actuellement: On)"
    fi
    
    # file_uploads
    FILE_UPLOADS=$(php -r "echo ini_get('file_uploads');" 2>/dev/null)
    if [ "$FILE_UPLOADS" = "1" ]; then
        print_success "Upload de fichiers activé"
    else
        print_error "Upload de fichiers désactivé"
    fi
    
    # session support
    if php -m 2>/dev/null | grep -qi "^session$"; then
        print_success "Support des sessions activé"
    else
        print_error "Support des sessions non disponible"
    fi
fi

###############################################################################
# 1.6 - Autres fonctions PHP
###############################################################################
print_header "1.6 - Autres fonctions PHP"

if command -v php &> /dev/null; then
    print_check "Fonctions PHP critiques"
    
    # Vérifier exec
    DISABLED_FUNCTIONS=$(php -r "echo ini_get('disable_functions');" 2>/dev/null)
    
    if echo "$DISABLED_FUNCTIONS" | grep -q "exec"; then
        print_error "La fonction 'exec' est désactivée (requise pour le module admin)"
    else
        print_success "La fonction 'exec' est disponible"
    fi
    
    # Vérifier set_time_limit
    if echo "$DISABLED_FUNCTIONS" | grep -q "set_time_limit"; then
        print_error "La fonction 'set_time_limit' est désactivée (requise pour le module admin)"
    else
        print_success "La fonction 'set_time_limit' est disponible"
    fi
fi

###############################################################################
# 1.7 - Locales
###############################################################################
print_header "1.7 - Locales"

print_check "Locales installées"

# Vérifier fr_FR
if locale -a 2>/dev/null | grep -qi "fr_FR"; then
    print_success "Locale fr_FR installée"
else
    print_error "Locale fr_FR non installée"
    print_info "Installation: sudo locale-gen fr_FR fr_FR.UTF-8"
fi

# Vérifier en_US.UTF-8
if locale -a 2>/dev/null | grep -qi "en_US.utf8\|en_US.UTF-8"; then
    print_success "Locale en_US.UTF-8 installée"
else
    print_error "Locale en_US.UTF-8 non installée (requise pour LC_NUMERIC)"
    print_info "Installation: sudo dpkg-reconfigure locales"
    print_info "Puis redémarrer Apache: sudo systemctl restart apache2"
fi

###############################################################################
# 1.8 - Sendmail
###############################################################################
print_header "1.8 - Sendmail"

print_check "Sendmail local"
if command -v sendmail &> /dev/null; then
    print_success "Sendmail trouvé"
    
    # Vérifier si sendmail peut être exécuté
    if [ -x "$(command -v sendmail)" ]; then
        print_success "Sendmail est exécutable"
    else
        print_warning "Sendmail n'est pas exécutable"
    fi
else
    print_error "Sendmail n'est pas installé"
    print_info "Installation: sudo apt-get install sendmail"
fi

# Test SMTP (basique)
print_check "Configuration SMTP"
if [ -f /etc/mail/sendmail.mc ] || [ -f /etc/mail/sendmail.cf ]; then
    print_success "Fichiers de configuration Sendmail trouvés"
    print_info "Vérifier manuellement la configuration SMTP"
else
    print_warning "Fichiers de configuration Sendmail non trouvés"
    print_info "Vérifier que Sendmail est correctement configuré"
fi

###############################################################################
# RÉSUMÉ
###############################################################################
print_header "RÉSUMÉ DE LA VÉRIFICATION"

echo ""
echo -e "${GREEN}Tests réussis:    $PASSED${NC}"
echo -e "${YELLOW}Avertissements:   $WARNINGS${NC}"
echo -e "${RED}Tests échoués:    $FAILED${NC}"
echo ""

if [ $FAILED -eq 0 ] && [ $WARNINGS -eq 0 ]; then
    echo -e "${GREEN}╔══════════════════════════════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║  ✓ Tous les prérequis sont satisfaits !                         ║${NC}"
    echo -e "${GREEN}║  Le système est prêt pour l'installation du CMS 7.1.x          ║${NC}"
    echo -e "${GREEN}╚══════════════════════════════════════════════════════════════════╝${NC}"
    exit 0
elif [ $FAILED -eq 0 ]; then
    echo -e "${YELLOW}╔══════════════════════════════════════════════════════════════════╗${NC}"
    echo -e "${YELLOW}║  ⚠ Prérequis satisfaits avec des avertissements                ║${NC}"
    echo -e "${YELLOW}║  Vérifiez les avertissements ci-dessus                         ║${NC}"
    echo -e "${YELLOW}╚══════════════════════════════════════════════════════════════════╝${NC}"
    exit 0
else
    echo -e "${RED}╔══════════════════════════════════════════════════════════════════╗${NC}"
    echo -e "${RED}║  ✗ Des prérequis ne sont pas satisfaits                        ║${NC}"
    echo -e "${RED}║  Corrigez les erreurs avant d'installer le CMS 7.1.x           ║${NC}"
    echo -e "${RED}╚══════════════════════════════════════════════════════════════════╝${NC}"
    exit 1
fi
