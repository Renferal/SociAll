cmake_minimum_required(VERSION 3.0)
project(main)
find_package(OpenCV REQUIRED)
add_executable(Face_Recog_Server src/main.cpp src/detectObject.cpp src/ImageUtils_0.7.cpp src/preprocessFace.cpp src/recognition.cpp)
target_link_libraries(Face_Recog_Server ${OpenCV_LIBS})
