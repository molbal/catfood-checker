# What is this?
This is a tiny web application built for my fiancée so she can compare cat food prices.   

# Running
1. Clone project
`git clone https://github.com/molbal/catfood.git`
2. Install composer packages
`composer install`
3. Create `.env` file based on the example `.env.example` 
4. Generate key
`php artisan key:generate` 
5. Insert data
```sql
INSERT INTO `stores` (`store_name`, `url`, `xpath`) VALUES
('ALDI', 'https://www.aldi.hu/hu/kinalatunkbol/allateledel-es-felszereles/allateledel-es-felszereles/reszletes-oldal/ps/p/whiskas-alutasakos-macskaeledel/', 'div.detail-box--price-box--price span.box--value'),
('Auchan', 'https://online.auchan.hu/shop/catalog/otthon-haztartas/allateledel-es-felszereles/macskaeledel/nedves-macskaeledelwhiskas-1-casserole-vegyes-valogatas-teljes-erteku-eledel-felnott-macskaknak-aszpikban-12-x-85-g.p71583/996388.v3608673', 'span.product-prices__new-price[itemprop=''price'']'),
('SPAR', 'https://www.spar.hu/onlineshop/whiskas-1-klasszikus-valogatas-teljes-erteku-nedves-eledel-felnott-macskaknak-martasban-12-x-100-g/p/360183009', 'div.productMainDetailsPriceLabels label.productDetailsPrice'),
('Tesco', 'https://bevasarlas.tesco.hu/groceries/hu-HU/products/2004020163664', 'span.value[data-auto=''price-value'']');
```