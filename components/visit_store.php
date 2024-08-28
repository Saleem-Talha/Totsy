<div class="mt-3">
                        <a href="https://www.daraz.pk/shop/totsy-pk" class="btn btn-daraz-store me-2" title="Visit Totsy.pk on Daraz">
                            <img src="../logo/daraz.png" alt="Daraz Store" style="width: 24px; height: 24px; vertical-align: middle;"> Totsy.pk
                        </a>
                        <a href="https://www.daraz.pk/shop/velvet-vibes" class="btn btn-other-daraz-store" title="Visit Velvet & Vibes on Daraz">
                            <img src="../logo/daraz.png" alt="Other Daraz Store" style="width: 24px; height: 24px; vertical-align: middle;"> Velvet & Vibes
                        </a>
                    </div>
                    <style>
                        .btn-daraz-store, .btn-other-daraz-store {
                            position: relative;
                            overflow: hidden;
                            transition: all 0.3s ease;
                            background-color: #f8f9fa;
                            color: #333;
                            text-decoration: none;
                            display: inline-block;
                        }
                        .btn-daraz-store:before, .btn-other-daraz-store:before {
                            content: "";
                            position: absolute;
                            top: 0;
                            left: -100%;
                            width: 100%;
                            height: 100%;
                            background: linear-gradient(120deg, transparent, rgba(0,0,0,0.1), transparent);
                            transition: all 0.6s;
                        }
                        .btn-daraz-store:hover:before, .btn-other-daraz-store:hover:before {
                            left: 100%;
                        }
                        .btn-daraz-store:hover, .btn-other-daraz-store:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                        }
                    </style>
                    <script>
                        document.querySelector('.btn-daraz-store').addEventListener('click', function(e) {
                            e.preventDefault();
                            window.open('https://www.daraz.pk/shop/totsy-pk', '_blank');
                        });
                        document.querySelector('.btn-other-daraz-store').addEventListener('click', function(e) {
                            e.preventDefault();
                            window.open('https://www.daraz.pk/shop/velvet-vibes', '_blank');
                        });
                    </script>