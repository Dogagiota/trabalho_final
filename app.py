from fastapi import FastAPI
from fastapi.responses import FileResponse
from fastapi.staticfiles import StaticFiles
from pydantic import BaseModel
import spacy

# Carrega o modelo spaCy
nlp = spacy.load("modelo_arquiteto")

app = FastAPI()

app.mount("/static", StaticFiles(directory="static"), name="static")

@app.get("/")
def home():
    return FileResponse("ia.html")

class Pedido(BaseModel):
    texto: str

@app.post("/classificar")
def classificar_texto(pedido: Pedido):
    # --- VALIDAÇÃO NOVA ---
    if len(pedido.texto.strip()) < 4:
        return {"resultado": {}} # Retorna vazio se for muito curto
    # ----------------------

    doc = nlp(pedido.texto)
    resultado = {label: float(score) for label, score in doc.cats.items() if score > 0.35}
    return {"resultado": resultado}
