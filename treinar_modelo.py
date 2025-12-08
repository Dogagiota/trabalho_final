import spacy
from spacy.training import Example
import random
from dataset import treino
import time # <-- NOVO: Importa o módulo time

# Labels do modelo
labels = [
    "residencial", "comercial", "corporativo", "paisagismo",
    "moderno", "tradicional", "minimalista", "contemporaneo", "industrial", "natural", "verde", "rustico", "classico", "futurista",
    "completo", "interiores", "exterior", "estrutural", "lixo"
]

# Cria modelo em branco
nlp = spacy.blank("pt")
# Adiciona o componente de classificação de texto
textcat = nlp.add_pipe("textcat_multilabel")
for label in labels:
    textcat.add_label(label)

# Prepara dataset
train_data = []
for item in treino:
    cats = {label: 0 for label in labels}
    
    # Define 1 para as categorias encontradas
    cats[item["tipo"]] = 1
    if item["estilo"] in labels:
        cats[item["estilo"]] = 1
    if item["parte"] in labels:
        cats[item["parte"]] = 1
        
    train_data.append((item["texto"], {"cats": cats}))

# Treinamento
optimizer = nlp.begin_training()

# --- INÍCIO DA CONTAGEM DE TEMPO ---
start_time = time.time() 

for epoch in range(30):
    random.shuffle(train_data)
    for texto, annotations in train_data:
        doc = nlp.make_doc(texto)
        example = Example.from_dict(doc, annotations)
        # Atualiza o modelo com o exemplo
        nlp.update([example], sgd=optimizer)

# --- FIM DA CONTAGEM DE TEMPO ---
end_time = time.time() 

# Salva modelo
output_dir = "modelo_arquiteto"
nlp.to_disk(output_dir)

# --- CÁLCULO E EXIBIÇÃO DA DURAÇÃO ---
duration = end_time - start_time
minutes = int(duration // 60)
seconds = duration % 60

print(f"Modelo treinado e salvo em '{output_dir}/'.")
print(f"✅ Tempo total de treinamento: {minutes} minutos e {seconds:.2f} segundos.")