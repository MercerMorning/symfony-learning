<?php

namespace App\DataFixtures;

use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $customer = $this->getReference(UserFixtures::CUSTOMERS[0]);
        $executor = $this->getReference(UserFixtures::EXECUTORS[0]);
        $ordersData = [
            [
                'customer' => $customer,
                'executor' => $executor,
                'description' => 'des',
                'status' => 2,
                'price' => 15.20
            ],
        ];
        foreach ($ordersData as $orderData) {
            $order = new Order();
            $order->setCustomer($orderData['customer']);
            $order->setExecutor($orderData['executor']);
            $order->setDescription($orderData['description']);
            $order->setStatus($orderData['status']);
            $order->setPrice($orderData['price']);
            $manager->persist($order);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
//        return [
//            UserFixtures::class,
//        ];
    }
}

