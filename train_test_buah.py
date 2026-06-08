"""
==============================================================
  MobileNetV2 - Klasifikasi Buah Segar & Busuk
  Dataset: Kaggle (fresh / rotten)
  Device : CPU
==============================================================
  Jalankan: python train_test_buah.py
==============================================================
"""

import os
import time
import copy
import torch
import torch.nn as nn
import torch.optim as optim
from torchvision import models, transforms, datasets
from torch.utils.data import DataLoader
import matplotlib.pyplot as plt
import numpy as np
from sklearn.metrics import classification_report, confusion_matrix
import seaborn as sns

# ─────────────────────────────────────────────
# 0. KONFIGURASI — sesuaikan jika perlu
# ─────────────────────────────────────────────
DATASET_DIR   = "dataset"          # folder berisi train/ dan test/
SAVE_DIR      = "hasil_training"   # folder output model & grafik
MODEL_NAME    = "mobilenetv2_buah.pth"

NUM_EPOCHS    = 10
BATCH_SIZE    = 16   # kecil agar aman di CPU
LEARNING_RATE = 0.001
NUM_WORKERS   = 0    # 0 = aman untuk Windows/CPU

os.makedirs(SAVE_DIR, exist_ok=True)
device = torch.device("cpu")
print(f"✅ Device: {device}")

# ─────────────────────────────────────────────
# 1. CEK JUMLAH FOTO PER FOLDER
# ─────────────────────────────────────────────
print("\n📂 Jumlah foto per folder:")
for split in ["train", "test"]:
    for kelas in ["fresh", "rotten"]:
        folder = os.path.join(DATASET_DIR, split, kelas)
        if os.path.exists(folder):
            jumlah = len([f for f in os.listdir(folder)
                          if f.lower().endswith((".jpg", ".jpeg", ".png", ".webp"))])
            print(f"   {split}/{kelas}: {jumlah} foto")
        else:
            print(f"   ⚠️  Folder tidak ditemukan: {folder}")

# ─────────────────────────────────────────────
# 2. TRANSFORM (augmentasi untuk train)
# ─────────────────────────────────────────────
data_transforms = {
    "train": transforms.Compose([
        transforms.Resize((224, 224)),
        transforms.RandomHorizontalFlip(),
        transforms.RandomRotation(15),
        transforms.ColorJitter(brightness=0.2, contrast=0.2),
        transforms.ToTensor(),
        transforms.Normalize([0.485, 0.456, 0.406],
                             [0.229, 0.224, 0.225]),
    ]),
    "test": transforms.Compose([
        transforms.Resize((224, 224)),
        transforms.ToTensor(),
        transforms.Normalize([0.485, 0.456, 0.406],
                             [0.229, 0.224, 0.225]),
    ]),
}

# ─────────────────────────────────────────────
# 3. LOAD DATASET
# ─────────────────────────────────────────────
image_datasets = {
    split: datasets.ImageFolder(
        root=os.path.join(DATASET_DIR, split),
        transform=data_transforms[split]
    )
    for split in ["train", "test"]
}

dataloaders = {
    split: DataLoader(
        image_datasets[split],
        batch_size=BATCH_SIZE,
        shuffle=(split == "train"),
        num_workers=NUM_WORKERS
    )
    for split in ["train", "test"]
}

class_names = image_datasets["train"].classes
num_classes = len(class_names)

print(f"\n🍎 Kelas: {class_names}")
print(f"   Total train : {len(image_datasets['train'])} foto")
print(f"   Total test  : {len(image_datasets['test'])} foto")

# ─────────────────────────────────────────────
# 4. MODEL MobileNetV2
# ─────────────────────────────────────────────
print("\n⚙️  Memuat MobileNetV2 pretrained...")
model = models.mobilenet_v2(weights=models.MobileNet_V2_Weights.IMAGENET1K_V1)

# Freeze semua layer kecuali classifier
for param in model.features.parameters():
    param.requires_grad = False

# Ganti classifier sesuai jumlah kelas
model.classifier[1] = nn.Linear(model.last_channel, num_classes)
model = model.to(device)

criterion = nn.CrossEntropyLoss()
optimizer = optim.Adam(model.classifier.parameters(), lr=LEARNING_RATE)
scheduler = optim.lr_scheduler.StepLR(optimizer, step_size=5, gamma=0.1)

# ─────────────────────────────────────────────
# 5. TRAINING
# ─────────────────────────────────────────────
print("\n🚀 Mulai Training...\n")

history = {"train_acc": [], "train_loss": [], "val_acc": [], "val_loss": []}
best_acc   = 0.0
best_model = copy.deepcopy(model.state_dict())

for epoch in range(NUM_EPOCHS):
    epoch_start = time.time()
    print(f"Epoch {epoch+1}/{NUM_EPOCHS}  {'─'*40}")

    for phase in ["train", "test"]:
        model.train() if phase == "train" else model.eval()

        running_loss    = 0.0
        running_correct = 0

        for inputs, labels in dataloaders[phase]:
            inputs, labels = inputs.to(device), labels.to(device)
            optimizer.zero_grad()

            with torch.set_grad_enabled(phase == "train"):
                outputs = model(inputs)
                loss    = criterion(outputs, labels)
                _, preds = torch.max(outputs, 1)

                if phase == "train":
                    loss.backward()
                    optimizer.step()

            running_loss    += loss.item() * inputs.size(0)
            running_correct += torch.sum(preds == labels).item()

        epoch_loss = running_loss / len(image_datasets[phase])
        epoch_acc  = running_correct / len(image_datasets[phase])

        tag = "train" if phase == "train" else "val"
        history[f"{tag}_loss"].append(epoch_loss)
        history[f"{tag}_acc"].append(epoch_acc)

        print(f"  {phase.upper():5s} — Loss: {epoch_loss:.4f}  Acc: {epoch_acc:.4f}")

        if phase == "test" and epoch_acc > best_acc:
            best_acc   = epoch_acc
            best_model = copy.deepcopy(model.state_dict())

    scheduler.step()
    elapsed = time.time() - epoch_start
    print(f"  ⏱  {elapsed:.1f} detik\n")

# Simpan model terbaik
save_path = os.path.join(SAVE_DIR, MODEL_NAME)
torch.save(best_model, save_path)
print(f"✅ Model terbaik disimpan → {save_path}")
print(f"✅ Best Val Accuracy      : {best_acc:.4f} ({best_acc*100:.2f}%)")

# ─────────────────────────────────────────────
# 6. GRAFIK TRAINING
# ─────────────────────────────────────────────
epochs_range = range(1, NUM_EPOCHS + 1)

fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(14, 5))

ax1.plot(epochs_range, history["train_acc"], label="Train Accuracy", marker="o")
ax1.plot(epochs_range, history["val_acc"],   label="Val Accuracy",   marker="o")
ax1.set_title("Akurasi Training")
ax1.set_xlabel("Epoch")
ax1.set_ylabel("Accuracy")
ax1.legend()
ax1.grid(True)

ax2.plot(epochs_range, history["train_loss"], label="Train Loss", marker="o", color="red")
ax2.plot(epochs_range, history["val_loss"],   label="Val Loss",   marker="o", color="orange")
ax2.set_title("Loss Training")
ax2.set_xlabel("Epoch")
ax2.set_ylabel("Loss")
ax2.legend()
ax2.grid(True)

plt.tight_layout()
grafik_path = os.path.join(SAVE_DIR, "Figure_training.png")
plt.savefig(grafik_path)
plt.show()
print(f"✅ Grafik training disimpan → {grafik_path}")

# ─────────────────────────────────────────────
# 7. TESTING FINAL + CLASSIFICATION REPORT
# ─────────────────────────────────────────────
print("\n🔍 Testing model terbaik pada data test...")

model.load_state_dict(best_model)
model.eval()

all_preds  = []
all_labels = []

with torch.no_grad():
    for inputs, labels in dataloaders["test"]:
        inputs = inputs.to(device)
        outputs = model(inputs)
        _, preds = torch.max(outputs, 1)
        all_preds.extend(preds.cpu().numpy())
        all_labels.extend(labels.numpy())

test_acc = sum(p == l for p, l in zip(all_preds, all_labels)) / len(all_labels)
print(f"\n{'='*50}")
print(f"  ✅ TEST ACCURACY FINAL : {test_acc*100:.2f}%")
print(f"{'='*50}\n")

print("📊 Classification Report:")
print(classification_report(all_labels, all_preds, target_names=class_names))

# ─────────────────────────────────────────────
# 8. CONFUSION MATRIX
# ─────────────────────────────────────────────
cm = confusion_matrix(all_labels, all_preds)

plt.figure(figsize=(7, 6))
sns.heatmap(cm, annot=True, fmt="d", cmap="Blues",
            xticklabels=class_names,
            yticklabels=class_names)
plt.title(f"Confusion Matrix\nTest Accuracy: {test_acc*100:.2f}%")
plt.ylabel("Aktual")
plt.xlabel("Prediksi")
plt.tight_layout()

cm_path = os.path.join(SAVE_DIR, "confusion_matrix.png")
plt.savefig(cm_path)
plt.show()
print(f"✅ Confusion matrix disimpan → {cm_path}")

print("\n✅ SELESAI! Semua hasil ada di folder hasil_training/")