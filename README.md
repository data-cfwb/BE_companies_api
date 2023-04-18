# API to retrieve information about belgian companies

API of belgian companies based on public OpenData from the [Banque Carrefour of Belgian government](https://kbopub.economie.fgov.be/kbo-open-data/login).

# Cookbook of BCE Open Data

<https://economie.fgov.be/sites/default/files/Files/Entreprises/BCE/Cookbook-BCE-Open-Data.pdf>

# Why this API?

This API is a simple way to get expose from the [Banque Carrefour of Belgian government](https://kbopub.economie.fgov.be/kbo-open-data/login) and from the [Open Data Wallonia-Brussels](https://odwb.be/) regarding the subsidies.

# How to use it?

1. Clone this repository ;
2. Get the data from the [Banque Carrefour of Belgian government](https://kbopub.economie.fgov.be/kbo-open-data/login) and put it in the `csv` folder ;
3. Get the data from ODWB regarding the subsidies and put it in the `csv` folder ;
4. Run the notebook `./notebooks/bce-prepare-data.ipynb` to prepare the data ;
5. Run the notebook `./notebooks/subsidies-prepare-data.ipynb` to prepare the data ;
6. Run `php artisan serve` to start the API on port `8000` ;
