<?php

namespace Database\Seeders;

use App\Models\DeviceModel;
use Illuminate\Database\Seeder;

class MikroTikProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                "name" => "hEX S (2025)",
                "image_url" => "https://cdn.mikrotik.com/web-assets/rb_images/2458_l.jpg",
                "short_description" => "A compact, affordable wired router featuring a 2.5G SFP port, 5x Gigabit Ethernet, PoE out, USB, and a fast dual-core CPU – ideal for homes, offices, or underfunded labs that need reliable performance.",
            ],
            [
                "name" => "RB5009UG+S+IN",
                "image_url" => "https://cdn.mikrotik.com/web-assets/rb_images/2065_l.jpg",
                "short_description" => "The ultimate heavy-duty home lab router with USB 3.0, 1G and 2.5G Ethernet and a 10G SFP+ cage. You can mount four of these new routers in a single 1U rackmount space! Unprecedented processing power in such a small form factor.",
            ],
            [
                "name" => "RB5009UPr+S+IN",
                "image_url" => "https://cdn.mikrotik.com/web-assets/rb_images/2190_l.jpg",
                "short_description" => "A new version of our popular heavy-duty RB5009 router with PoE-in and PoE-out on all ports. Perfect for small and medium ISPs. 2.5 Gigabit Ethernet & 10 Gigabit SFP+, numerous powering options.",
            ],
            [
                "name" => "ROSE Data server (RDS)",
                "image_url" => "https://cdn.mikrotik.com/web-assets/rb_images/2438_l.jpg",
                "short_description" => "RDS is a high-performance, all-in-one storage, 100G networking, and container platform designed for enterprise environments. Featuring 20 U.2 NVMe storage slots and a special RouterOS Edition for Storage & Compute (ROSE).",
            ],
            [
                "name" => "RB5009UPr+S+OUT",
                "image_url" => "https://cdn.mikrotik.com/web-assets/rb_images/2250_l.jpg",
                "short_description" => "An outdoor version of our best-selling heavy-duty PoE router. Extensive power redundancy for the best price! 7x Gigabit Ethernet ports, 1x 2.5 Gigabit Ethernet, 10G SFP+, 1GB of RAM, 1GB NAND, modern quad-core CPU, 9 (!) powering options, durable IP66 waterproof enclosure.",
            ],
            [
                "name" => "RB4011iGS+RM",
                "image_url" => "https://cdn.mikrotik.com/web-assets/rb_images/1633_l.jpg",
                "short_description" => "Powerful 10xGigabit port router with a Quad-core 1.4Ghz CPU, 1GB RAM, SFP+ 10Gbps cage and desktop case with rack ears.",
            ],
            [
                "name" => "L009UiGS-RM",
                "image_url" => "https://cdn.mikrotik.com/web-assets/rb_images/2267_l.jpg",
                "short_description" => "The L009 legendary product line is better than ever. Featuring a powerful ARM CPU, 512 MB of RAM, 8x Gigabit Ethernet ports, SFP, and a full-size USB 3.0 port. A rackmount kit is included.",
            ],
            [
                "name" => "hEX (2024)",
                "image_url" => "https://cdn.mikrotik.com/web-assets/rb_images/2408_l.jpg",
                "short_description" => "The classic hEX refresh: same price, twice the performance! Modern ARM CPU, 512 MB of RAM, 5x Gigabit Ethernet ports, and a full-size USB 2.0 port.",
            ],
            [
                "name" => "CCR2216-1G-12XS-2XQ",
                "image_url" => "https://cdn.mikrotik.com/web-assets/rb_images/2122_l.jpg",
                "short_description" => "Unleash the power of 100 Gigabit networking with L3 Hardware Offloading! This router is a seamless upgrade for existing CCR1072 setups.",
            ],
            [
                "name" => "CCR2116-12G-4S+",
                "image_url" => "https://cdn.mikrotik.com/web-assets/rb_images/2115_l.jpg",
                "short_description" => "Double the performance: we used the feedback from the CCR2004 to create a powerhouse with a 16-core ARM CPU. It crushes previous 36-core TILE CPUs.",
            ],
            [
                "name" => "hEX S",
                "image_url" => "https://cdn.mikrotik.com/web-assets/rb_images/1539_l.jpg",
                "short_description" => "5x Gigabit Ethernet, SFP, Dual Core 880MHz CPU, 256MB RAM, USB, microSD, RouterOS L4.",
            ],
            [
                "name" => "CCR2004-16G-2S+",
                "image_url" => "https://cdn.mikrotik.com/web-assets/rb_images/2563_l.jpg",
                "short_description" => "Crushes all previous CCR models in single-core performance. 16x Gigabit Ethernet ports, USB, 2x10G SFP+ cages.",
            ],
            [
                "name" => "CCR2004-16G-2S+PC",
                "image_url" => "https://cdn.mikrotik.com/web-assets/rb_images/2173_l.jpg",
                "short_description" => "Up to 300% faster than previous CCR1009 routers. Luxury you deserve. 16x Gigabit Ethernet ports, 2x10G SFP+ cages.",
            ],
        ];

        foreach ($products as $product) {
            DeviceModel::updateOrCreate(
                ['manufacturer' => 'MikroTik', 'model_name' => $product['name']],
                [
                    'device_type' => 'router',
                    'image_url' => $product['image_url'],
                    'description' => $product['short_description'],
                    'max_throughput' => null,
                ]
            );
        }
    }
}
