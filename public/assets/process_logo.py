from PIL import Image

def process():
    # Open original image
    img = Image.open('public/assets/logo.png')
    
    # 1. Crop the Map Pin Icon (Top section)
    # The pin is located in the upper center
    # Box: (left, upper, right, lower)
    # Let's crop it tightly around the orange pin
    icon_box = (350, 180, 674, 560)
    icon = img.crop(icon_box)
    icon.save('public/assets/logo_icon.png')
    print("Icon cropped successfully.")
    
    # 2. Crop the Text section (UMUHUZA .ONLINE + Tagline)
    # Box: (left, upper, right, lower)
    text_box = (140, 565, 884, 780)
    text_img = img.crop(text_box)
    text_img.save('public/assets/logo_text.png')
    print("Text cropped successfully.")
    
    # 3. Combine them horizontally to make a wide header logo
    # Let's resize the text to match the icon's height nicely
    # Icon height is 560-180 = 380, width is 674-350 = 324
    # Text height is 780-565 = 215, width is 884-140 = 744
    # Let's target a final canvas height of 380
    new_text_height = 380
    new_text_width = int(text_img.width * (new_text_height / text_img.height))
    text_resized = text_img.resize((new_text_width, new_text_height), Image.Resampling.LANCZOS)
    
    # Create wide canvas: Icon width + spacing + Text width
    spacing = 40
    canvas_width = icon.width + spacing + new_text_width
    canvas_height = 380
    
    # Create canvas with white background (or transparent if we want, but original has white background)
    canvas = Image.new('RGB', (canvas_width, canvas_height), color='white')
    
    # Paste Icon and Text
    canvas.paste(icon, (0, 0))
    canvas.paste(text_resized, (icon.width + spacing, 0))
    
    # Save the wide logo
    canvas.save('public/assets/logo_wide.png')
    print("Wide logo created successfully.")

if __name__ == '__main__':
    process()
