# 🚧 Acidentes de Trânsito em BRs do Rio Grande do Sul

Projeto de análise de acidentes em rodovias federais do Rio Grande do Sul utilizando dados da PRF, IBGE e GeoPandas.

---

# 📌 Objetivo

Realizar o tratamento, análise e visualização de dados de acidentes em BRs do RS, identificando padrões relacionados a:

* tipos de acidentes
* gravidade
* horários críticos
* rodovias mais perigosas

---

# 🗂️ Estrutura do Repositório

```bash id="n2olr9"
acidentes-br-rs/
│
├── notebooks/
│   ├── 01_etl.ipynb
│   ├── 02_analise.ipynb
│   └── 03_visualizacao.ipynb
│
├── imagens/
│   ├── heatmap.png
│   ├── top10_brs.png
│   └── serie_temporal.png
│
├── dados/
│   ├── acidentes-rs.geojson
│   └── municipios-rs.geojson
│
├── requirements.txt
├── README.md
└── .gitignore
```

---

# 🌎 Fontes de Dados

* PRF — dados de acidentes
* IBGE — municípios do RS
* OpenStreetMap / DNIT — malha viária

---

# 📊 Análises Realizadas

* Acidentes por tipo
* Gravidade dos acidentes
* Horários com maior incidência
* Top 10 BRs mais perigosas
* Série temporal de acidentes
* Heatmap de densidade

---

# 🛠️ Tecnologias Utilizadas

* Python
* Pandas
* GeoPandas
* Matplotlib
* Folium
* Jupyter Notebook

