import csv
import os
import re

BASE_PATH = os.path.dirname(__file__)
DATASET_PATH = os.path.join(BASE_PATH, "dataset")

FILES = {
    "movies": os.path.join(DATASET_PATH, "movies.csv"),
    "ratings": os.path.join(DATASET_PATH, "ratings.csv"),
    "tags": os.path.join(DATASET_PATH, "tags.csv"),
    "users": os.path.join(DATASET_PATH, "users.txt"),
}

def escape(value: str) -> str:
    return value.replace("'", "''")

def extract_year(title: str):
    match = re.search(r"\((\d{4})\)$", title.strip())
    if match:
        year = int(match.group(1))
        clean_title = title[: match.start()].strip()
        return clean_title, year
    return title, None

def write_batch_insert(writer, table_name, columns, rows):
    if not rows:
        return
    writer.write(f"INSERT INTO {table_name} ({', '.join(columns)}) VALUES\n")
    writer.write(",\n".join(rows))
    writer.write(";\n")

BATCH_SIZE = 5000

def generate_movies(writer):
    writer.write("DROP TABLE IF EXISTS movies;\n")
    writer.write("CREATE TABLE movies (id INTEGER PRIMARY KEY, title TEXT, year INTEGER, genres TEXT);\n")
    
    rows = []
    with open(FILES["movies"], encoding="utf-8") as f:
        reader = csv.reader(f)
        next(reader)
        for row in reader:
            movie_id, title, genres = row
            clean_title, year = extract_year(title)
            year_sql = str(year) if year is not None else "NULL"
            escaped_title = escape(clean_title)
            escaped_genres = escape(genres)
            rows.append(f"({movie_id}, '{escaped_title}', {year_sql}, '{escaped_genres}')")
            
            if len(rows) >= BATCH_SIZE:
                write_batch_insert(writer, "movies", ["id", "title", "year", "genres"], rows)
                rows = []
    
    if rows:
        write_batch_insert(writer, "movies", ["id", "title", "year", "genres"], rows)

def generate_ratings(writer):
    writer.write("DROP TABLE IF EXISTS ratings;\n")
    writer.write("CREATE TABLE ratings (id INTEGER PRIMARY KEY, user_id INTEGER, movie_id INTEGER, rating REAL, timestamp TEXT);\n")
    
    rows = []
    with open(FILES["ratings"], encoding="utf-8") as f:
        reader = csv.reader(f)
        next(reader)
        for idx, row in enumerate(reader, start=1):
            user_id, movie_id, rating, ts = row
            rows.append(f"({idx}, {user_id}, {movie_id}, {rating}, '{ts}')")
            
            if len(rows) >= BATCH_SIZE:
                write_batch_insert(writer, "ratings", ["id", "user_id", "movie_id", "rating", "timestamp"], rows)
                rows = []
    
    if rows:
        write_batch_insert(writer, "ratings", ["id", "user_id", "movie_id", "rating", "timestamp"], rows)

def generate_tags(writer):
    writer.write("DROP TABLE IF EXISTS tags;\n")
    writer.write("CREATE TABLE tags (id INTEGER PRIMARY KEY, user_id INTEGER, movie_id INTEGER, tag TEXT, timestamp TEXT);\n")
    
    rows = []
    with open(FILES["tags"], encoding="utf-8") as f:
        reader = csv.reader(f)
        next(reader)
        for idx, row in enumerate(reader, start=1):
            user_id, movie_id, tag, ts = row
            escaped_tag = escape(tag)
            rows.append(f"({idx}, {user_id}, {movie_id}, '{escaped_tag}', '{ts}')")
            
            if len(rows) >= BATCH_SIZE:
                write_batch_insert(writer, "tags", ["id", "user_id", "movie_id", "tag", "timestamp"], rows)
                rows = []
    
    if rows:
        write_batch_insert(writer, "tags", ["id", "user_id", "movie_id", "tag", "timestamp"], rows)

def generate_users(writer):
    writer.write("DROP TABLE IF EXISTS users;\n")
    writer.write("CREATE TABLE users (id INTEGER PRIMARY KEY, name TEXT, email TEXT, gender TEXT, register_date TEXT, occupation TEXT);\n")
    
    rows = []
    with open(FILES["users"], encoding="utf-8") as f:
        for i, line in enumerate(f, 1):
            row = line.strip().split("|")
            if len(row) < 6:
                continue
            u_id, name, email, gender, reg_date, occupation = row[:6]
            escaped_name = escape(name)
            escaped_email = escape(email)
            escaped_occupation = escape(occupation)
            rows.append(f"({u_id}, '{escaped_name}', '{escaped_email}', '{gender}', '{reg_date}', '{escaped_occupation}')")
            
            if len(rows) >= BATCH_SIZE:
                write_batch_insert(writer, "users", ["id", "name", "email", "gender", "register_date", "occupation"], rows)
                rows = []
    
    if rows:
        write_batch_insert(writer, "users", ["id", "name", "email", "gender", "register_date", "occupation"], rows)

def main():
    output_path = os.path.join(BASE_PATH, "db_init.sql")
    with open(output_path, "w", encoding="utf-8") as sql_file:
        generate_movies(sql_file)
        generate_ratings(sql_file)
        generate_tags(sql_file)
        generate_users(sql_file)

if __name__ == "__main__":
    main()