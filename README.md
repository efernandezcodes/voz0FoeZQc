# CodeTest

**Submission by Edward Fernandez.**

## Description

| Env   | Store Front URL                     | Admin URL                                   |
|-------|:------------------------------------|:--------------------------------------------|
| Local | https://app.carrierenterprise.test/ | https://app.carrierenterprise.test/backend/ |

[Warden](https://warden.dev/) is used for local environment setup. See the [Installing Warden](https://docs.warden.dev/installing.html) docs page for further info and procedures.

This code test submission runs on a Magento 2.4.4-p1 (Community Edition) installation with two custom modules:
- CodeTest_Price
- CodeTest_PriceGraphQL



### CodeTest_Price

The `CodeTest_Price` module implements a base price data model and repository to handle Magento product price data. By default, it returns a product's unit price from the Magento catalog.

The `CodeTest_Price` module can integrate with a price information API to retrieve external price data. 

Configuration is available via the Magento Admin in `Stores > Settings > Configuration > Catalog > Catalog > Price Information Integration`

| Setting | Description                              | Default Value                  |
|---------|------------------------------------|---------|
| Enabled | Retrieve price data from an external API | `No`                                                    |
| API Endpoint | External API endpoint URL                | `https://stagecerewards.carrierenterprise.com/v1/price` |
| Debug | When enabled, price API responses are logged to `/var/log/price.log`                       | `No`                                                    |


### CodeTest_PriceGraphQL

The `CodeTest_PriceGraphQL` module adds a GraphQL query to retrieve product price information for a Magento product.

#### Syntax
```
productPrice(
  product_id: Int
): ProductPrice
```

#### Input attributes
|Attribute | Data Type | Description |
|---------|---------|---------|
| `product_id` | Int | The identifier of a Magento product |

#### Output attributes
The query returns a ProductPrice object containing the following information:

|Attribute | Data Type | Description               |
|---------|---------|---------------------------|
| `product_id` | Int | Magento Product Entity ID |
| `unit_price` | Float | Product Unit Price        |
| `display_price` | String | Product Display Price     |





## Acceptance Criteria

This code test submission implements a data layer to fetch product price data from an external price information API.

---

`productPrice` GraphQL query:
```
query productPrice($productId: Int!) {
  productPrice (product_id: $productId) {
      product_id
      unit_price
      display_price
  }
}
```

GraphQL query variables:
```json
{"productId": 2}
```

---

cURL request to `productPrice` GraphQL query:
```bash
curl --location --request POST 'https://app.carrierenterprise.test/graphql' \
--header 'Content-Type: application/json' \
--data-raw '{"query":"query productPrice($productId: Int!) {\r\n productPrice (product_id: $productId) {\r\n product_id\r\n unit_price\r\n display_price}}","variables":{"productId":2}}'
```

`productPrice` GraphQL query response:
```json
{
    "data": {
        "productPrice": {
            "product_id": 2,
            "unit_price": 3990.53,
            "display_price": "$3,990.53"
        }
    }
}
```

---

#### Additional Acceptance Criteria

- Price API responses can be logged to `/var/log/price.log` when the `CodeTest_Price` module's debug mode is enabled via the Magento Admin. It uses the `\CodeTest\Price\Logger\PriceLogger`
- The `ProductPrice` GraphQL type has been extended to include a `display_price` field which returns a price currency translated string value in the `productPrice` GraphQL query response.

#### Additional Features

- The `CodeTest_Price` module is configurable via the Magento Admin in `Stores > Settings > Configuration > Catalog > Catalog > Price Information Integration` 
- When the Price Information Integration is disabled, the `CodeTest_Price` PriceRepository will return product price data based on the Magento Catalog.


