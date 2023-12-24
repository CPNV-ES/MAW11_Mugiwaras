<br />
<div align="center">

  <h1 align="center">MAW11_MUGIWARAS</h1>

  <p align="center">
    An awesome framework to create a website !
    <br />
    <br />
    <a href="https://github.com/CPNV-ES/MAW11_Mugiwaras_Framework/issues/new?assignees=&labels=bug&projects=&template=bug_report.md&title=%5BBUG%5D">Report Bug</a>
    ·
    <a href="https://github.com/CPNV-ES/MAW11_Mugiwaras_Framework/issues/new?assignees=&labels=enhancement&projects=&template=feature_request.md&title=">Request Feature</a>
  </p>
</div>

<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
  </ol>
</details>

# About The Project

This is a php web framework that uses the MVC pattern. It is a project for the Web Applications Development course at the CPNV. The goal of this project is to create a framework that should ease the creation of a website.

# Getting Started

## Prerequisites

* PHP 8.2.9 (TS)
* xdebug 3.2.2
* composer 2.6.2

### How to install the requirements

#### PHP

> Use your favourite package manager to install PHP 8.2.9 (TS). Or follow this [link](https://www.php.net/manual/install.php) that redirects to the official PHP download page.

#### xdebug

> Follow this [link](https://xdebug.org/docs/install) that redirects to the official xdebug install documentation.

#### composer

> Follow this [link](https://getcomposer.org/download/) that redirects to the official composer download page.

<!-- The following installation procedure is not valid.  -->
### Installation

#### Installing as a composer package

1. add the repository to your composer.json
```json
{
  "require": {
      "mugiwaras/framework": "dev-main"
    },
  "repositories": [
    {
      type": "vcs",
      url": "https://github.com/CPNV-ES/MAW11_Mugiwaras_Framework"
    }
  ]
}
```

2. Then use composer to install the package

```shell
composer install 
```

3. copy the .env.example or set the required environment variables that you can find in the vendor under Mugiwaras\framework\.env.example

4. All done, you can now use the framework as you wish !

#### Working as a contributor

1. Clone the repository.

```shell
git clone https://github.com/CPNV-ES/MAW11_Mugiwaras_Framework
```

2. install the dependencies with composer

```shell
composer install
```

3. copy the .env.example or set the required environment variables.

4. You are all set! You can now work with the framework.

# Credits

The section system of the framework is greatly inspired from devanych vue-renderer which did an amazing work.
You can find the repository [here](https://github.com/devanych/view-renderer)
