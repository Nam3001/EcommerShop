<?php

class ProductDAO
{
    public $db;

    public function __construct()
    {
        $this->db = new DBHelper();
    }

    public function count() {
        try {
            $sql = "select count(*) as count from product";
            $res = $this->db->select($sql);
            return $res[0]['count'];
        } catch(Exception $e)
        {
            return 0;
        }
    }

    public function insert($name, $desc, $unit, $images, $quantity, $categoryId, $brandId, $status)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $now = date('Y-m-d H:i:s');

            $this->db->insert('product', ['name' => $name, 'description' => $desc, 'unit_id' => $unit, 'quantity' => $quantity, 'category_id' => $categoryId, 'brand_id' => $brandId, 'status' => $status]);
            $lastInsertId = $this->db->lastInsertId();

//            $this->db->insert('price', ['product_id' => $lastInsertId, 'price' => $price, 'datetime' => $now]);

            foreach ($images as $imagePath) {
                $this->db->insert('image', ['path' => $imagePath, 'product_id' => $lastInsertId]);
            }
            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function updateQuantity($productId, $quantity) {
        try {
            // chuan bi thong tin thoi gian de set updatedAt
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $now = date('Y-m-d H:i:s');
            return $this->db->update('product', ['quantity' => $quantity, 'updatedAt' => $now], "id = $productId");
        } catch(Exception $e) {
            return false;
        }
    }

    public function update($prodId, $name, $desc, $unit, $brand_id, $category_id, $status, $imagesToInsert, $imageIdListToDelete)
    {
        try {
            // delete image in uploads/productImages
            $uploadDirPath = ROOT_DIR . 'uploads/productImages';
            foreach ($imageIdListToDelete as $imageId) {
                $imageToDelete = (new DBHelper())->select("select * from image where id = :id", [':id' => $imageId])[0];
                print_r($imageToDelete);
                $imagename = pathinfo($imageToDelete['path'], PATHINFO_BASENAME);
                $imagePath = $uploadDirPath . '/' . $imagename;
                unlink($imagePath);
            }

            // chuan bi thong tin thoi gian de set updatedAt
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $now = date('Y-m-d H:i:s');

            // update product
            $this->db->update('product', ['name' => $name, 'description' => $desc, 'unit_id' => $unit, 'brand_id' => $brand_id, 'category_id' => $category_id, 'status' => $status, 'updatedAt' => $now], "id = $prodId");

            // insert price
//            $this->db->insert('price', ['price' => $price, 'datetime' => $now, 'product_id' => $prodId]);

            // insert new images
            foreach ($imagesToInsert as $imagePath) {
                $this->db->insert('image', array('path' => $imagePath, 'product_id' => $prodId));
            }

            // delete images which want to delete
            foreach ($imageIdListToDelete as $imageId) {
                $this->db->delete('image', "id = $imageId");
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function selectSimilarProducts($cateId, $prodId)
    {
        $query = 'select product.id as productId, product.name, product.description, price, image.path as image, quantity, status, updatedAt from product 
left join 
(
    select price.* from price JOIN
    (select product_id, max(datetime) as datetime from price group by product_id) as latestPrice
    on price.product_id = latestPrice.product_id and price.datetime = latestPrice.datetime
) as price
on product.id = price.product_id
left join
(SELECT product_id, path, min(id) as minId
FROM image
GROUP BY product_id) as image
on product.id = image.product_id
where product.category_id = :categoryId and product.id != :productId and product.status = 1';
        $stm = $this->db->prepare($query);
        $stm->bindParam(':categoryId', $cateId);
        $stm->bindParam(':productId', $prodId);

        $stm->execute();
        return $stm->fetchAll();
    }

    public function selectProductById($id)
    {
        $query = "select product.*, image.path as image from product
join (select * from image where product_id = :id) as image
on image.product_id = product.id
where product.id = :id;
";
        return $this->db->select($query, [':id' => $id]);
    }

    public function delete($id)
    {
        try {
            return $this->db->delete('product', "id = $id");
        } catch (Exception $e) {
            return 0;
        }
    }

    public function selectAll() {
        $query = "select product.id as productId, product.name as productName, product.description as productDescription, image.path as image, quantity, brand.name as brand, category.name as category, product.category_id, product.brand_id, status, updatedAt from product 
left join 
(SELECT product_id, path, min(id) as minId
FROM image
GROUP BY product_id) as image
on product.id = image.product_id
left join brand
on product.brand_id = brand.id
left join category
on product.category_id = category.id";
        try {
            return $this->db->select($query);
        } catch (Exception $e) {
            return array();
        }
    }

    public function selectByPage($page, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $query = "select product.id as productId, product.name as productName, product.description as productDescription, image.path as image, quantity, brand.name as brand, category.name as category, product.category_id, product.brand_id, status, updatedAt from product 
left join 
(SELECT product_id, path, min(id) as minId
FROM image
GROUP BY product_id) as image
on product.id = image.product_id
left join brand
on product.brand_id = brand.id
left join category
on product.category_id = category.id limit $perPage offset $offset ";
        return $this->db->select($query);
    }

    public function selectOrderByUpdatedAt()
    {
        $query = 'select product.id as productId, product.name as productName, product.description as productDescription, price, image.path as image, quantity, brand.name as brand, category.name as category, product.category_id, product.brand_id, status, updatedAt from product 
left join 
(
    select price.* from price JOIN
    (select product_id, max(datetime) as datetime from price group by product_id) as latestPrice
    on price.product_id = latestPrice.product_id and price.datetime = latestPrice.datetime
) as price
on product.id = price.product_id
left join
(SELECT product_id, path, min(id) as minId
FROM image
GROUP BY product_id) as image
on product.id = image.product_id
left join brand
on product.brand_id = brand.id
left join category
on product.category_id = category.id
order by updatedAt desc';
        return $this->db->select($query);
    }

    public function selectOrderByLowPrice()
    {
        $query = "select product.id as productId, product.name as productName, product.description as productDescription, price, image.path as image, quantity, brand.name as brand, category.name as category, product.category_id, product.brand_id, status, updatedAt from product 
left join 
(
    select price.* from price JOIN
    (select product_id, max(datetime) as datetime from price group by product_id) as latestPrice
    on price.product_id = latestPrice.product_id and price.datetime = latestPrice.datetime
) as price
on product.id = price.product_id
left join
(SELECT product_id, path, min(id) as minId
FROM image
GROUP BY product_id) as image
on product.id = image.product_id
left join brand
on product.brand_id = brand.id
left join category
on product.category_id = category.id
order by price";
        return $this->db->select($query);
    }

    public function selectOrderByHighPrice()
    {
        $query = "select product.id as productId, product.name as productName, product.description as productDescription, price, image.path as image, quantity, brand.name as brand, category.name as category, product.category_id, product.brand_id, status, updatedAt from product 
left join 
(
    select price.* from price JOIN
    (select product_id, max(datetime) as datetime from price group by product_id) as latestPrice
    on price.product_id = latestPrice.product_id and price.datetime = latestPrice.datetime
) as price
on product.id = price.product_id
left join
(SELECT product_id, path, min(id) as minId
FROM image
GROUP BY product_id) as image
on product.id = image.product_id
left join brand
on product.brand_id = brand.id
left join category
on product.category_id = category.id
order by price desc";
        return $this->db->select($query);
    }

    public function getTwoNewestPrice($productId)
    {
        $query = 'select * from price where product_id = :productId
order by datetime desc
limit 2';

        return $this->db->select($query, [':productId' => $productId]);
    }

    public function checkBusinessProduct($productId) {
        try {
            $sql = "select status from product where id = :productId";
            $res = $this->db->select($sql, [':productId' => $productId]);
            if(count($res) === 0) return false;

            $status = intval($res[0]['status']);
            return $status === 1;
        } catch (Exception $e) {
            return false;
        }
    }

    public function selectProductPrice($productId) {
        $sql = "select * from price where product_id = :productId and ((dateStart is null and dateEnd is null) or (dateStart is null and dateEnd is not null and dateEnd >= CURRENT_DATE()) or (dateStart is not null and dateStart <= CURRENT_DATE() and dateEnd is null) or (dateStart <= CURRENT_DATE() and dateEnd >= CURRENT_DATE())) order by isOriginalPrice, datetime desc limit 2";
        try {
            $res = $this->db->select($sql, [':productId' => $productId]);
            return $res;
        } catch(Exception $e) {
            return array();
        }
    }

    public function selectProductSalePriceButDontApplyYet($productId) {
        $sql = "select * from price where product_id = :productId and isOriginalPrice = 0 and ((dateStart is null and dateEnd is null) or (dateStart is null and dateEnd >= CURRENT_DATE())  or (dateStart is not null and dateEnd is not null and dateStart <= dateEnd) or (dateStart is not null and dateEnd is null))  order by datetime desc limit 1
;
";
        try {
            $res = $this->db->select($sql, [':productId' => $productId]);
            return $res;
        } catch(Exception $e) {
            return array();
        }
    }
}
