# Autoloader

Autoloader universel

## Objets

* ClassInfos
* Decorators
* Handler
* IAutoLoadable

### ClassInfos

Manipule un namespace sous forme string.

### BaseDir

Est un dossier dans lequel rechercher une classe. Représent le nom d'une classe sous sa forme de fichier attendus

### Handler

Objet ayant pour responsabilté de présenté le callback de spl_autoload_register sous la forme d'une fonction static handle()

### IAutoLoadable

Est implémentée par BaseDir
