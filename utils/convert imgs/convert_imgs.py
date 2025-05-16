#!/usr/bin/env python3
import os
import sys
import logging
import math
import time
import warnings
from PIL import Image, ImageOps
import multiprocessing

# Suppress PIL warnings
warnings.filterwarnings("ignore", category=UserWarning, module="PIL")

# enable AVIF support in Pillow
try:
    import avif  # pillow-avif-plugin
except ImportError:
    pass  # silently ignore

LOG_FILE = "conversion_errors.log"

def prompt_int(prompt, default=None, min_val=None, max_val=None):
    while True:
        raw = input(f"{prompt}{' ['+str(default)+']' if default is not None else ''}: ").strip()
        if not raw and default is not None:
            return default
        try:
            val = int(raw)
            if (min_val is not None and val < min_val) or (max_val is not None and val > max_val):
                raise ValueError()
            return val
        except ValueError:
            rng = ""
            if min_val is not None and max_val is not None:
                rng = f" between {min_val} and {max_val}"
            print(f"Please enter an integer{rng}.")

def get_folder_size_mb(path):
    total = 0
    for dirpath, _, filenames in os.walk(path):
        for f in filenames:
            fp = os.path.join(dirpath, f)
            if os.path.isfile(fp):
                total += os.path.getsize(fp)
    return total / (1024 * 1024)

def process_image_task(args):
    in_path, out_path, target_size, quality = args
    try:
        with Image.open(in_path) as im:
            icc = im.info.get("icc_profile")
            im = im.convert("RGBA")
            src_w, src_h = im.size
            tgt_w, tgt_h = target_size

            scale = max(tgt_w / src_w, tgt_h / src_h)
            new_w = math.ceil(src_w * scale)
            new_h = math.ceil(src_h * scale)
            im_resized = im.resize((new_w, new_h), Image.LANCZOS)

            left = (new_w - tgt_w) // 2
            top = (new_h - tgt_h) // 2
            im_cropped = im_resized.crop((left, top, left + tgt_w, top + tgt_h))

            out_im = im_cropped.convert("RGB")

            os.makedirs(os.path.dirname(out_path), exist_ok=True)

            save_kwargs = {
                "format": "WEBP",
                "quality": quality,
                "method": 6,
            }
            if icc:
                save_kwargs["icc_profile"] = icc

            out_im.save(out_path, **save_kwargs)
            return True
    except Exception as e:
        logging.error(f"{in_path}: {e}")
        return False

def main():
    logging.basicConfig(
        filename=LOG_FILE,
        level=logging.ERROR,
        format="%(asctime)s %(levelname)s: %(message)s"
    )

    width  = prompt_int("Enter target width (px)",  default=None, min_val=1)
    height = prompt_int("Enter target height (px)", default=None, min_val=1)
    quality= prompt_int("Enter WebP quality (0-100)",  default=80, min_val=0, max_val=100)
    size   = (width, height)

    # Get the directory where the script itself is located
    script_dir = os.path.dirname(os.path.abspath(__file__))
    src_root = os.path.join(script_dir, "input_images")
    dst_root = os.path.join(script_dir, "output_images")

    if not os.path.isdir(src_root):
        print(f"\nError: The input directory was not found.")
        print(f"Expected to find: {src_root}")
        print(f"Please create this directory and place the images you want to convert inside it.")
        sys.exit(1)

    before_mb = get_folder_size_mb(src_root)

    tasks = []
    for dirpath, _, filenames in os.walk(src_root):
        for fname in filenames:
            in_fp = os.path.join(dirpath, fname)
            rel = os.path.relpath(in_fp, src_root)
            base, _ = os.path.splitext(rel)
            out_fp = os.path.join(dst_root, base + ".webp")
            tasks.append((in_fp, out_fp, size, quality))

    start_time = time.time()
    with multiprocessing.Pool() as pool:
        results = pool.map(process_image_task, tasks)
    end_time = time.time()

    after_mb = get_folder_size_mb(dst_root)
    total = len(results)
    succeeded = sum(results)
    failed = total - succeeded
    elapsed = end_time - start_time

    print("\nConversion complete.")
    print(f"Input size: {before_mb:.2f} MB | Output size: {after_mb:.2f} MB")
    print(f"Processed {total} files in {elapsed:.2f} seconds.")
    print(f"Converted: {succeeded} | Failed: {failed}")

if __name__ == "__main__":
    main()
